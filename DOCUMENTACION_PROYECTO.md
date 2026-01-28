# Documentacion del proyecto ECOMMERCE_GC

Generado: 2026-01-28
Generado por: Cristopher Brenes Rivera

## Resumen
Este repositorio contiene una aplicacion de eCommerce basada en Bagisto (Laravel 11) con arquitectura modular bajo `packages/Webkul`. Incluye backend Laravel, frontend con Vite, y servicios auxiliares (MySQL, Re is, Elasticsearch, Kibana, Mailpit) definidos en `docker-compose.yml`.

## Stack y dependencias principales

### Backend (PHP/Laravel)
- PHP requerido: ^8.2
- Laravel: ^11.0
- Bagisto: 2.3.10
- Modulos clave: Bagisto packages (ver seccion Arquitectura)
- Otros servicios: Elasticsearch, Redis, Pusher, PayPal, OpenAI PHP SDK

### Frontend
- Vite 5
- laravel-vite-plugin 1.x
- axios

## Requisitos y configuracion

### Variables de entorno
Fuente: `.env.example`.
- App: `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `APP_ADMIN_URL`, `APP_TIMEZONE`
- Locales: `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`
- Moneda: `APP_CURRENCY`
- DB: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_PREFIX`
- Cache y sesiones: `CACHE_STORE`, `CACHE_PREFIX`, `SESSION_DRIVER`, `SESSION_LIFETIME`, `SESSION_PATH`, `SESSION_DOMAIN`
- Cola y broadcast: `QUEUE_CONNECTION`, `BROADCAST_CONNECTION`
- Redis: `REDIS_CLIENT`, `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT`
- Mail: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
- Notificaciones admin/cliente: `ADMIN_MAIL_ADDRESS`, `ADMIN_MAIL_NAME`, `CONTACT_MAIL_ADDRESS`, `CONTACT_MAIL_NAME`
- AWS S3: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_USE_PATH_STYLE_ENDPOINT`
- Vite: `VITE_APP_NAME`, `VITE_HOST`, `VITE_PORT`

Nota: `.env` contiene secretos. No versionar.

### Servicios via Docker (Laravel Sail)
Archivo: `docker-compose.yml`
- `laravel.test`: app PHP 8.3 con Vite
- `mysql`: MySQL 8.0
- `redis`: Redis
- `elasticsearch`: 7.17.0
- `kibana`: 7.17.0
- `mailpit`: servidor SMTP de desarrollo

Puertos:
- App: `APP_PORT` (default 80)
- Vite: `VITE_PORT` (default 5173)
- MySQL: `FORWARD_DB_PORT` (default 3306)
- Redis: `FORWARD_REDIS_PORT` (default 6379)
- Elasticsearch: 9200/9300
- Kibana: 5601
- Mailpit: 1025/8025

## Arquitectura y estructura

### Directorios principales
- `app/`: codigo Laravel (Console, Http, Listeners, Mail, Models, Providers)
- `packages/Webkul/`: modulos Bagisto (ver lista)
- `config/`: configuraciones Laravel/Bagisto
- `resources/`: assets, vistas y temas
- `public/`: assets compilados y storage publico
- `routes/`: rutas base (rutas reales viven en paquetes)
- `database/`: migraciones, seeds
- `storage/`: logs, cache, uploads
- `tests/` y `packages/**/tests`: pruebas

### Modulos (packages/Webkul)
- Admin, Attribute, BookingProduct, CartRule, CatalogRule, Category, Checkout, CMS
- Core, Customer, DataGrid, DataTransfer, DebugBar, FPC, GDPR
- Installer, Inventory, MagicAI, Marketing, Notification
- Payment, Paypal, Product, Rule, Sales, Shipping, Shop
- Sitemap, SocialLogin, SocialShare, Tax, Theme, User

### Autoload PSR-4
Definido en `composer.json` para `App\`, `Database\*` y `Webkul\*`.

## Integracion con ERP (Comandos)
Ubicacion: `app/Commands`.

### `app:sync-erp-products` (SyncErpProducts)
- Proposito: sincroniza productos desde ERP (modelo `App\Models\ErpProduct`) hacia Bagisto.
- Flujo principal:
  - Recorre todos los items del ERP y crea/actualiza productos por `sku`.
  - Limpia `product_attribute_values` del producto antes de reinsertar atributos.
  - Actualiza `products` y construye atributos (name, url_key, price, weight, status, descriptions, tax).
  - Inserta valores de atributos en `product_attribute_values` por tipo de atributo.
  - Asigna categorias: crea una categoria si no existe y agrega "Todos los productos" (id 5).
  - Actualiza inventario en `product_inventories`.
  - Vincula imagenes desde `storage/app/public/productos/{SKU}*.jpg`.
  - Si no hay imagenes para un SKU nuevo, inactiva el producto.
  - Inactiva productos que ya no existen en ERP (status = 0).
  - Reindexa productos con `indexer:index`.
- Notificaciones: envia correo con resumen (exitoso o fallido).

### `app:sync-exchange-rate` (SyncExchangeRate)
- Proposito: sincroniza tipo de cambio desde ERP hacia Bagisto.
- Fuente ERP: conexion `sqlsrv_erp`, tabla `VIEW_ECOMMERCE_TIPO_CAMBIO_DOLAR`.
- Logica:
  - Lee `MONTO`, valida y calcula el rate como `1 / monto`.
  - Actualiza `currency_exchange_rates` y `currencies` para USD.
  - Limpia cache con `config:clear`.
- Notificaciones: envia correo con resultado y valores.

### Dependencias implicitas
- Base de datos ERP accesible via conexion `sqlsrv_erp`.
- Modelo `App\Models\ErpProduct` para productos.
- Mailable `App\Mail\ProductSyncReport` para reportes.
- Permisos de escritura en `storage/app/public/productos` para imagenes.

## Exportacion de pedidos a ERP (Listeners)
Ubicacion: `app/Listeners`.

### `App\Listeners\SyncOrderERP`
- Proposito: exporta pedidos desde Bagisto al ERP al dispararse el listener.
- Inserta cabecera en `ECOMMERCE_ORDER` (SQL Server) con datos de cliente, envio, totales, moneda, metodos y estado.
- Inserta lineas en `ECOMMERCE_ORDER_LINE` por cada item:
  - SKU, nombre, cantidad, precio, impuestos y total.
  - Determina la categoria: usa la primera categoria distinta de "Todos los productos" / "All Products".
- Usa la conexion `sqlsrv_erp`.
- Logging: registra exito o error en logs.
- Notificacion de error: envia correo con detalle del pedido y error.

## Build y comandos

### Composer (PHP)
- Script post-install: copia `.env.example` a `.env` y genera `APP_KEY`.
- Ejecuta `composer install` para dependencias.

### NPM/Vite
Scripts en `package.json`:
- `npm run dev`: modo desarrollo
- `npm run build`: build de produccion

### Vite
Entradas: `resources/css/app.css`, `resources/js/app.js`.

## Pruebas
Configuracion en `phpunit.xml`:
- Suites: Admin (Feature), Core (Unit), DataGrid (Unit), Shop (Feature)
- Entorno de test con cache y sesiones en memoria, cola sync.

## Calidad de codigo
- `pint.json`: preset `laravel` y alineacion de `=>`.

## Seguridad
Archivo `SECURITY.md`:
- Reportar vulnerabilidades a `support@bagisto.com` y no publicarlas.

## Contribucion
Archivo `CONTRIBUTING.md`:
- Preferir PRs sobre reportes de bugs.
- Bugs a branch `development`, features mayores a `master`.
- No commitear assets compilados.
- Estilo: PSR-2 y PSR-4.

## Conducta
Archivo `CODE_OF_CONDUCT.md`:
- Basado en Contributor Covenant 1.4.
- Conducta respetuosa y entorno libre de acoso.

## Actualizaciones
Archivo `UPGRADE.md`:
- Guida de upgrade v2.2 -> v2.3.
- PHP >= 8.2, Laravel 11, cambios en keys de `.env`.
- Cambios en estructura de app y paquetes.

## Licencia
Licencia MIT (ver `LICENSE`).

## Notas operativas
- Las rutas base en `routes/web.php` estan vacias; los paquetes registran sus rutas.
- Assets publicos se generan en `public/build` via Vite.
- Storage publico: `public/storage` (link a `storage/app/public`).

## Fuentes dentro del repo
- `README.md` (descripcion general de Bagisto)
- `composer.json`, `package.json` (dependencias y scripts)
- `.env.example` (plantilla de variables)
- `docker-compose.yml` (servicios)
- `phpunit.xml`, `pint.json`, `SECURITY.md`, `CONTRIBUTING.md`, `CODE_OF_CONDUCT.md`, `UPGRADE.md`
