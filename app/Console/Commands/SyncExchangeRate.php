<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductSyncReport; // Reutilizamos  Mailable de reportes
use Illuminate\Support\Facades\Log;

class SyncExchangeRate extends Command
{
    /**
     * El nombre y firma del comando.
     */
    protected $signature = 'app:sync-exchange-rate';

    /**
     * La descripción del comando.
     */
    protected $description = 'Sincroniza el tipo de cambio desde el ERP hacia Bagisto';

    public function handle()
    {
        $startTime = now();
        $this->info("Iniciando sincronización de tipo de cambio...");

        try {
            // 1. OBTENER EL TIPO DE CAMBIO DESDE EL ERP
            // Ajusta 'TIPO_CAMBIO_TABLA' y los nombres de columna según tu ERP
            $erpRate = DB::connection('sqlsrv_erp')
                ->table('VIEW_ECOMMERCE_TIPO_CAMBIO_DOLAR')
                ->first();

            if (!$erpRate) {
                throw new \Exception("No se encontró información de tipo de cambio en el ERP.");
            }

            $nuevoValor = floatval($erpRate->MONTO);
            $tipoCambioERP = $nuevoValor;

            if ($nuevoValor <= 0) {
                throw new \Exception("El tipo de cambio recibido no es válido: {$nuevoValor}");
            } else {
                $nuevoValor = 1 / $nuevoValor;
            }

            $this->info("Tipo de cambio detectado en ERP: {$nuevoValor}");

            // 1. Buscamos primero el ID de la moneda USD en la tabla currencies
            $currency = DB::table('currencies')->where('code', 'USD')->first();

            if (! $currency) {
                throw new \Exception("No se encontró la moneda USD en la tabla 'currencies'.");
            }

            $currencyId = $currency->id;

            // 2. Actualizamos la tabla de Exchange Rates usando el ID
            $updatedExchangeRate = DB::table('currency_exchange_rates')
                ->where('target_currency', $currencyId)
                ->update([
                    'rate'       => $nuevoValor, // El valor 1 / ERP
                    'updated_at' => now()
                ]);

            // 3. Actualizamos también la tabla currencies por ID
            // (Es fundamental que ambas tablas coincidan)
            $updatedCurrency = DB::table('currencies')
                ->where('id', $currencyId)
                ->update([
                    'updated_at'    => now()
                ]);

            if ($updatedExchangeRate || $updatedCurrency) {
                $this->info("Sincronización exitosa para USD (ID: {$currencyId}).");
            } else {
                $this->warn("No se realizaron cambios. Es posible que el valor ya fuera el mismo.");
            }

            // 4. LIMPIAR CACHÉ
            $this->call('config:clear');

            // 5. NOTIFICACIÓN
            $this->sendNotification('EXITOSA', "Dólar ERP: ₡$tipoCambioERP\nRate Bagisto: " . number_format($nuevoValor, 8));
        } catch (\Exception $e) {
            // NOTIFICACIÓN FALLIDA
            $errorDetails = "Error actualizando tipo de cambio: {$e->getMessage()}\n" .
                "Línea: {$e->getLine()}";

            $this->error($errorDetails);
            $this->sendNotification('FALLIDA', $errorDetails);

            return 1;
        }
    }

    protected function sendNotification($status, $details)
    {
        try {
            Mail::to('soporte@centralveterinaria.com')
                ->cc(['gsalas@grupocoris.com', 'crbrenes@grupocoris.com', 'jecorrales@grupocoris.com'])
                ->send(new ProductSyncReport("SYNC TIPO DE CAMBIO $status", $details));

            $this->info("Correo de notificación enviado.");
        } catch (\Exception $e) {
            Log::error("Error enviando correo de tipo de cambio: " . $e->getMessage());
        }
    }
}
