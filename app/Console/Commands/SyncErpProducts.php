<?php

namespace App\Console\Commands;

use App\Models\ErpProduct;
use Illuminate\Console\Command;
use Webkul\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductSyncReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncErpProducts extends Command
{
    protected $signature = 'app:sync-erp-products';
    protected $description = 'Sincroniza productos desde ERP SQL Server hacia Bagisto';

    public function handle()
    {
        $productRepo = app(ProductRepository::class);
        $startTime = now();
        $this->info("Iniciando sincronización...");

        try {

            $porcentaje_avance = 0.0;
            $cont_articulos = 0;
            $total_items = ErpProduct::count();

            foreach (ErpProduct::all() as $erpItem) {
                $cont_articulos++;
                $porcentaje_avance = ($cont_articulos / $total_items) * 100;
                $this->info("Progreso: {$porcentaje_avance}%");

                $sku = trim($erpItem->SKU);
                $articulo_nuevo = false;

                // Validar existencia
                // Buscar si el producto ya existe en Bagisto
                $product = $productRepo->findOneByField('sku', $sku);
                $status = $product->status ?? null;

                if (!$product) {
                    $articulo_nuevo = true;
                    $this->info("Creando nuevo SKU: {$sku}");
                    $product = $productRepo->create([
                        'type'                => 'simple',
                        'attribute_family_id' => 1,
                        'sku'                 => $sku,
                    ]);
                } else {
                    $this->info("Actualizando SKU existente: {$sku}");

                    // Eliminamos los valores previos para que el update inserte de forma limpia
                    DB::table('product_attribute_values')->where('product_id', $product->id)->delete();
                }

                // 2. ACTUALIZACIÓN DIRECTA: Actualizamos la tabla de productos base
                DB::table('products')->where('id', $product->id)->update([
                    'sku' => $sku,
                    'updated_at' => now(),
                ]);

                // 3. PREPARAR DATOS PARA ACTUALIZACIÓN (Común para nuevos y existentes)
                $attributeData = [
                    'name'               => $erpItem->NOMBRE,
                    'url_key'            => Str::slug($erpItem->NOMBRE) . '-' . $sku,
                    'price'              => floatval($erpItem->PRECIO),
                    'weight'             => floatval($erpItem->weight ?? 1),
                    'status'             => $status ?? $erpItem->ACTIVO,
                    'visible_individually' => 1,
                    'short_description'  => $erpItem->NOMBRE,
                    'description'        => $erpItem->NOMBRE,
                    'tax_category_id'      => 1,
                ];

                // Insertar atributos uno a uno (simulando lo que hace el repositorio pero sin tocar imágenes)
                foreach ($attributeData as $code => $value) {
                    $attribute = app('Webkul\Attribute\Repositories\AttributeRepository')->findOneByField('code', $code);

                    if ($attribute) {
                        // Detectar la columna correcta (text_value, float_value, integer_value, etc.)
                        $column = $attribute->type . '_value';

                        // Si el atributo es de tipo 'price', la columna es 'float_value'
                        if ($attribute->type == 'price') $column = 'float_value';
                        // Si el tipo es 'boolean', la columna es 'boolean_value'
                        if ($attribute->type == 'boolean') $column = 'boolean_value';
                        // Si el tipo es 'text', la columna es 'textarea_value'
                        if ($attribute->type == 'text' || $attribute->type == 'textarea') $column = 'text_value';
                        // IMPORTANTE: tax_category_id es tipo select, usa integer_value
                        if ($attribute->type == 'select') {
                            $column = 'integer_value';
                        }

                        DB::table('product_attribute_values')->insert([
                            'product_id'   => $product->id,
                            'attribute_id' => $attribute->id,
                            $column        => $value, // Insertamos en la columna dinámica
                            'channel'      => 'default',
                            'locale'       => 'es',
                            'unique_id'    => $product->id . '|' . $attribute->id . '|default|es' // Clave única de Bagisto
                        ]);
                    }
                }

                // 4. ASIGNAR CATEGORÍAS E INVENTARIOS
                $categoriaId = $this->getOrCreateCategory($erpItem->CATEGORIA ?? 'General');
                $categoriaTodosId = 5; // ID de "Todos los productos"

                // Asignar categoría específica (Condición: producto + categoría)
                DB::table('product_categories')->updateOrInsert(
                    ['product_id' => $product->id, 'category_id' => $categoriaId],
                    ['category_id' => $categoriaId]
                );

                // Asignar categoría general "Todos los productos"
                DB::table('product_categories')->updateOrInsert(
                    ['product_id' => $product->id, 'category_id' => $categoriaTodosId],
                    ['category_id' => $categoriaTodosId]
                );

                DB::table('product_inventories')->updateOrInsert(
                    ['product_id' => $product->id, 'inventory_source_id' => 1],
                    ['qty' => intval($erpItem->CANTIDAD ?? 0)]
                );

                // 5. VINCULAR IMÁGENES MANUALMENTE
                if ($articulo_nuevo) {
                    $pattern = storage_path("app/public/productos/{$sku}*.jpg");
                    $matches = glob($pattern);

                    if (!empty($matches)) {
                        foreach ($matches as $index => $file) {
                            $imagePath = 'productos/' . basename($file);

                            DB::table('product_images')->insert([
                                'product_id' => $product->id,
                                'path'       => $imagePath,
                            ]);
                        }

                        $this->info("Se vincularon " . count($matches) . " imágenes para el SKU: {$sku}");
                    } else {
                        $this->warn("Imagen física no encontrada para SKU: {$sku} en path: {$pattern}, eliminando artículo creado.");
                        // Eliminamos los valores previos y artículo creado
                        DB::table('product_attribute_values')->where('product_id', $product->id)->delete();
                        DB::table('products')->where('id', $product->id)->delete();
                    }
                }
            }

            /* Inhabilitar productos que ya no están en el ERP */
            $this->info("Inhabilitando productos que ya no están en el ERP...");

            // 1. Obtener todos los SKUs presentes en el ERP
            $erpSkus = ErpProduct::pluck('SKU')->map(fn($sku_old) => trim($sku_old))->toArray();

            if (!empty($erpSkus)) {
                // 2. Buscar el ID del atributo 'status'
                $statusAttribute = app('Webkul\Attribute\Repositories\AttributeRepository')->findOneByField('code', 'status');

                if ($statusAttribute) {
                    // 3. Obtener IDs de productos en Bagisto que NO están en el ERP
                    $productsToInactivate = DB::table('products')
                        ->whereNotIn('sku', $erpSkus)
                        ->pluck('id');

                    if ($productsToInactivate->count() > 0) {
                        // 4. Actualizar el valor del atributo 'status' a 0 (Inactivo)
                        DB::table('product_attribute_values')
                            ->whereIn('product_id', $productsToInactivate)
                            ->where('attribute_id', $statusAttribute->id)
                            ->update(['boolean_value' => 0]);

                        $this->info("Se desactivaron " . $productsToInactivate->count() . " productos.");
                    }
                }
            }

            $this->info("Reindexando productos...");
            $this->call('indexer:index');
            $this->info("Sincronización finalizada.");

            // SI TODO SALE BIEN:
            $duration = $startTime->diffInMinutes(now());
            $details = "Sincronización completada exitosamente. \n" .
                "Artículos procesados: $cont_articulos \n" .
                "Tiempo total: $duration minutos.";

            $this->sendNotification('EXITOSA', $details);
        } catch (\Exception $e) {
            // SI ALGO FALLA:
            $errorDetails = "Error en la línea {$e->getLine()}: {$e->getMessage()} \n" .
                "Archivo: {$e->getFile()}";

            $this->error("Error detectado: " . $e->getMessage());
            $this->sendNotification('FALLIDA', $errorDetails);

            return 1; // Código de error para el sistema
        }
    }

    protected function getOrCreateCategory($nombreCategoria)
    {
        $slug = Str::slug($nombreCategoria);

        // Verificar si ya existe
        $existing = DB::table('categories')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.name', $nombreCategoria)
            ->first();

        if ($existing) {
            return $existing->category_id;
        }

        // Crear categoría directamente
        $categoryId = DB::table('categories')->insertGetId([
            'parent_id' => 1,
            'display_mode' => 'products_only',
            'status' => 1,
            'position' => 1,
            'logo_path' => null,
            'banner_path' => null,
            'additional' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        DB::table('category_translations')->insert([
            'category_id' => $categoryId,
            'locale' => 'es',
            'name' => $nombreCategoria,
            'description' => null,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'slug'        => Str::slug($nombreCategoria),
        ]);

        return $categoryId;
    }

    protected function sendNotification($status, $details)
    {

        try {
            Mail::to('soporte@centralveterinaria.com')->cc(['gsalas@grupocoris.com', 'crbrenes@grupocoris.com','jecorrales@grupocoris.com'])->send(new ProductSyncReport("SYNC PRODUCTOS $status", $details));
            $this->info("Correo de notificación enviado.");
        } catch (\Exception $e) {
            $this->error("No se pudo enviar el correo: " . $e->getMessage());
        }
    }
}
