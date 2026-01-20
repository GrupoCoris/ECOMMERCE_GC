<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SyncOrderERP
{
    public function handle($order)
    {
        try {
            // Obtener direcciones
            $shippingAddress = $order->shipping_address;
            $billingAddress = $order->billing_address;

            // 1. PREPARAR CABECERA (Datos extendidos)
            $orderData = [
                'ORDER_ID'        => $order->id,
                'CUSTOMER_ID'        => $order->customer_id, // ID en el Ecommerce
                'CUSTOMER_EMAIL'     => $order->customer_email,
                'CUSTOMER_FIRST_NAME' => $order->customer_first_name,
                'CUSTOMER_LAST_NAME'  => $order->customer_last_name,

                // Dirección de Envío
                'IDENTIFICATION_NUMBER'       => $billingAddress->vat_id ?? null, //obtener vat_id de Billing
                'SHIP_ADDRESS'       => $shippingAddress->address ?? null,
                'SHIP_CITY'          => $shippingAddress->city ?? null,
                'SHIP_STATE'         => $shippingAddress->state ?? null,
                'SHIP_POSTCODE'      => $shippingAddress->postcode ?? null,
                'SHIP_COUNTRY'       => $shippingAddress->country ?? null,
                'SHIP_PHONE'         => $shippingAddress->phone ?? null,

                // Totales y moneda
                'CURRENCY'          => $order->order_currency_code,
                'SUB_TOTAL'          => floatval($order->sub_total),
                'SHIPPING_AMOUNT'    => floatval($order->shipping_amount),
                'TAX_AMOUNT'         => floatval($order->tax_amount),
                'DISCOUNT_AMOUNT'    => floatval($order->discount_amount),
                'TOTAL'             => floatval($order->grand_total),

                // Método de pago y envío
                'PAYMENT_METHOD'     => $order->payment->method_title ?? $order->payment->method,
                'SHIPPING_METHOD'    => $order->shipping_title ?? $order->shipping_description,

                'STATUS'            => $order->status,
                'CREATED_AT'         => $order->created_at->format('Y-m-d H:i:s'),
            ];

            // 2. INSERTAR CABECERA EN SQL SERVER
            DB::connection('sqlsrv_erp')->table('ECOMMERCE_ORDER')->insert($orderData);

            // 3. INSERTAR PRODUCTOS (Detalle)
            foreach ($order->items as $item) {
                //Obtener el producto real de Bagisto para acceder a sus categorías
                $product = $item->product;
                $categoryName = 'Sin Categoría';

                if ($product) {
                    // Obtenemos todas las categorías asociadas al producto
                    $categories = $product->categories;

                    // Buscamos la primera categoría cuyo nombre (traducido) no sea el excluido
                    $firstCategory = $categories->first(function ($category) {
                        // Bagisto maneja el nombre a través de traducciones automáticamente
                        $name = $category->name;
                        return $name != 'Todos los productos' && $name != 'All Products';
                    });

                    if ($firstCategory) {
                        $categoryName = $firstCategory->name;
                    }
                }

                DB::connection('sqlsrv_erp')->table('ECOMMERCE_ORDER_LINE')->insert([
                    'ORDER_ID'   => $order->id, // Relación con ExternalID
                    'SKU'        => $item->sku,
                    'NAME'       => $item->name,
                    'CATEGORY'    => $categoryName,
                    'QUANTITY'   => $item->qty_ordered,
                    'UNIT_PRICE' => floatval($item->price),
                    'TAX_AMOUNT'  => floatval($item->tax_amount),
                    'TOTAL_ITEM'  => floatval($item->total),
                ]);
            }

            Log::info("Pedido {$order->id} sincronizado con éxito al ERP.");
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error("Error sincronizando pedido #" . ($order->id ?? 'unknown') . ": " . $errorMessage);

            // 4. ENVIAR CORREO DE ERROR AL ADMINISTRADOR
            $this->sendErrorEmail($order, $errorMessage);
        }
    }

    /**
     * Envía un correo simple con el detalle del error.
     */
    protected function sendErrorEmail($order, $error)
    {
        try {
            $data = [
                'order_id' => $order->id ?? 'N/A',
                'error'    => $error,
                'date'     => now()->toDateTimeString()
            ];

            // Opción rápida: Mail Raw (puedes cambiar el correo a tu gusto)
            Mail::raw("Error de Sincronización ERP\n\nPedido ID: {$data['order_id']}\nFecha: {$data['date']}\nError: {$data['error']}", function ($message) use ($data) {
                $message->to('soporte@centralveterinaria.com')
                    ->cc(['gsalas@grupocoris.com', 'crbrenes@grupocoris.com', 'jecorrales@grupocoris.com'])
                    ->subject("FALLO Sincronización Pedido #{$data['order_id']} Bagisto - ERP");
            });
        } catch (\Exception $mailEx) {
            Log::error("No se pudo enviar el correo de notificación de error: " . $mailEx->getMessage());
        }
    }
}
