# KuroCute: tienda y facturación en Laravel 13

Tiendita de fans de Kuromi (peluches, figuras, maquillaje y motos) con un back office para pedidos, clientes, facturas, catálogo y perfiles. Los clientes y pedidos de ejemplo son ficticios; las ilustraciones y fotos son las imágenes originales del proyecto (`public/images`, `public/productos`).

> **Aviso:** proyecto escolar de fans, sin fines comerciales. Kuromi, My Melody y sus imágenes pertenecen a sus respectivos dueños (Sanrio). Las fotos de productos de terceros se usan solo como ejemplo.

![Laravel](https://img.shields.io/badge/Laravel-13.44-FF2D20) ![PHP](https://img.shields.io/badge/PHP-8.5-777BB4) ![MySQL](https://img.shields.io/badge/MySQL-8.4-4479A1) ![Tailwind](https://img.shields.io/badge/Tailwind-4.3-38BDF8) ![Vite](https://img.shields.io/badge/Vite-8.3-646CFF) ![Tests](https://img.shields.io/badge/tests-89%20passing-1f8a5e)

![Portada de la tienda](docs/screenshots/tienda-portada-claro.png)

## Stack

| Capa | Versión |
| --- | --- |
| PHP | 8.5 (imagen `php:8.5-cli`), requiere `^8.4` |
| Laravel | 13.44 |
| Laravel Breeze (Blade) | 2.4.2 |
| barryvdh/laravel-dompdf | 3.1.2 (dompdf 3.1.6) |
| PHPUnit | 13.4 |
| Vite / laravel-vite-plugin | 8.3 / 3.2 |
| Tailwind CSS | 4.3 (plugin `@tailwindcss/vite`, `@tailwindcss/forms`) |
| Alpine.js | 3.17 (+ collapse, focus) |
| Axios | 1.21 |
| Chart.js | 4.6 |
| MySQL | 8.4 (Docker) |
| Correo local | Mailpit |

Tipografías: Fredoka (titulares redondeados) y Nunito (texto), empaquetadas en el build con el soporte de fuentes de `laravel-vite-plugin`. Paleta original del proyecto: morado `#7b4397` / `#6a0dad`, lavanda `#f3ecfb`, rosa y negro; el modo oscuro es una "noche Kuromi".

## Funciones

**Tienda pública**
- Portada con collage de stickers de Kuromi, cinta animada con la galería de ilustraciones, buscador, categorías con portada y productos favoritos.
- Catálogo con búsqueda instantánea (Alpine + AJAX a `/catalogo/buscar`, con debounce), filtros por categoría, orden por precio o nombre y la URL sincronizada.
- Vista rápida del producto en modal y ficha de producto con relacionados; fotos reales de cada producto.
- Pedido tipo carrito (sesión): cajón lateral, cantidades limitadas por stock, confirmación sin cuenta. Al confirmar se crea el pedido, se registra o reutiliza al cliente por correo y se descuenta el stock.
- Kuromi también en los estados vacíos, el login/registro, la página 404 y el back office.
- Pie de página completo: marca, redes sociales en estilo sticker (marcadas como "Próximamente"), columnas Tienda, Rincón Kuromi (enlaces a cada categoría real), Ayuda, Mi cuenta (cambia según la sesión) y Contacto, y créditos. El back office y el login tienen un pie discreto con los mismos créditos.
- Páginas de ayuda: **Preguntas frecuentes** (`/preguntas-frecuentes`, acordeón con el flujo real de pedido, pago, factura y recogida en Tantoyuca), **Envíos y entregas** (`/envios-y-entregas`) y **Contacto** (`/contacto`): formulario con validación, CSRF, campo trampa contra bots y límite de 5 envíos por minuto; el mensaje llega por correo (`MensajeContactoMail`, con "responder a" del cliente) al buzón `MAIL_CONTACTO_ADDRESS` (en local se ve en Mailpit).

**Back office** (`/dashboard`, `/admin/...`)
- Dashboard con KPIs (cobrado del mes y variación, ventas cobradas, por cobrar, ticket promedio), gráfica de ingresos por mes (cobrado vs por cobrar), dona de facturas por estado, mejores clientes y facturas recientes. Cada gráfica tiene tabla de datos.
- Facturas: listado con búsqueda, pestañas por estado, filtro por forma de pago y rango de fechas; formulario con cálculo de IVA en vivo y carga de PDF/XML con vista previa; detalle; **Descargar PDF** (dompdf); vista previa y reenvío del correo.
- Correo `FacturaCreadaMail` en HTML con el PDF generado adjunto (y el XML si existe). Se envía al crear la factura.
- Pedidos: listado por estado, cambio de estado y **Generar factura** desde el pedido (IVA desglosado de precios con IVA incluido).
- Clientes con foto o logotipo (vista previa al subir), RFC validado, historial de facturas.
- Productos y categorías (las antiguas "secciones").
- Perfiles: `Administrador` puede crear, editar y eliminar; `Consulta` (asignado al registrarse) solo lee. Se controla con el gate `administrar`.
- Modo claro y oscuro (respeta la preferencia del sistema y se recuerda), animaciones que respetan `prefers-reduced-motion`, interfaz en español y adaptada a móvil.

## Correr con Docker

Solo necesitas Docker. El `docker-compose.yml` levanta PHP 8.5, MySQL 8.4, Mailpit y un contenedor de Node que compila los assets.

```bash
git clone <este repositorio> kurocute && cd kurocute
docker compose up -d --build
```

La primera vez el contenedor `app` instala dependencias con Composer, crea `.env` desde `.env.example`, genera `APP_KEY`, migra y siembra datos de ejemplo (tarda un par de minutos).

- Tienda: http://localhost:8000
- Back office: http://localhost:8000/login
  - Administrador: `admin@example.com` / `password`
  - Solo lectura: `consulta@example.com` / `password`
- Correos capturados (Mailpit): http://localhost:8025

Comandos utiles:

```bash
docker compose exec app php artisan test                  # pruebas
docker compose exec app php artisan migrate:fresh --seed  # reiniciar datos
docker compose down -v                                     # apagar y borrar volumenes
```

Para trabajar los estilos con recarga en caliente puedes usar Node en tu maquina: `npm install && npm run dev`.

### Sin Docker

PHP 8.4+ con `pdo_mysql`, `gd`, `zip` e `intl`, Composer, Node 20.19+ y MySQL:

```bash
cp .env.example .env          # ajusta DB_* y MAIL_*
composer install && npm install && npm run build
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Datos de ejemplo

`DatabaseSeeder` carga los catálogos base (`CatalogosSeeder`) y `DemoSeeder`:
3 categorías (Peluches Kuromi, Maquillaje, Motos), 18 productos con las fotos originales del proyecto (se copian al disco `public` al sembrar), 18 clientes ficticios con correos `@example.com`, unos 45 pedidos repartidos en el último año y facturas en todos los estados, con cobros en el mes en curso. Al sembrar se genera un PDF por factura (dompdf) y un XML tipo CFDI 4.0 marcado como documento de demostración sin validez fiscal.

## Pruebas

89 pruebas (301 aserciones) con PHPUnit 13 sobre SQLite en memoria:

- 25 originales de Breeze (registro, login, verificación de correo, contraseñas, perfil) y ejemplo.
- `TiendaTest`: portada, búsqueda por texto y descripción, filtro por categoría y orden, productos ocultos, ficha de producto.
- `CarritoTest`: agregar, límite de stock, agotados, actualizar y quitar, confirmar pedido, reutilizar cliente, privacidad del resumen.
- `AccesoTest`: rutas protegidas (invitados al login), perfil de consulta solo lectura, administrador con acceso completo, perfil asignado al registrarse.
- `FacturaTest`: alta con PDF/XML y correo enviado, adjuntos del correo, validación de archivos y folio, **descarga con `application/pdf`**, vista previa del correo, filtros, edición y borrado, numeración consecutiva.
- `BackOfficeTest`: KPIs del dashboard, clientes con imagen y RFC, facturar pedidos, estados de pedido, productos y categorías, perfiles.
- `PieDePaginaTest`: páginas de ayuda, enlaces del pie a páginas y categorías reales (sin enlaces `#`), créditos, "Mi cuenta" según la sesión y pie del back office.
- `ContactoTest`: token CSRF en el formulario, envío del correo a la tienda con "responder a", mensaje de éxito, validación, campo trampa y contenido del correo.
- `ImportesFacturaTest` (unit): cálculo y desglose de IVA.

```bash
docker compose exec app php artisan test
```

## Capturas

| | |
| --- | --- |
| ![Portada oscura](docs/screenshots/tienda-portada-oscuro.png) | ![Catálogo con búsqueda](docs/screenshots/catalogo-busqueda-claro.png) |
| ![Vista rápida](docs/screenshots/producto-vista-rapida-claro.png) | ![Ficha de producto](docs/screenshots/producto-ficha-oscuro.png) |
| ![Dashboard](docs/screenshots/admin-dashboard-claro.png) | ![Dashboard oscuro](docs/screenshots/admin-dashboard-oscuro.png) |
| ![Facturas](docs/screenshots/admin-facturas-claro.png) | ![Formulario de factura](docs/screenshots/admin-factura-formulario-claro.png) |
| ![Detalle de factura](docs/screenshots/admin-factura-detalle-claro.png) | ![PDF de factura](docs/screenshots/factura-pdf.png) |
| ![Correo de factura](docs/screenshots/correo-factura.png) | ![Pedidos](docs/screenshots/admin-pedidos-claro.png) |

| ![Login](docs/screenshots/login-oscuro.png) | ![Página 404](docs/screenshots/pagina-404-claro.png) |
| ![Pie de página](docs/screenshots/tienda-pie-claro.png) | ![Pie de página oscuro](docs/screenshots/tienda-pie-oscuro.png) |
| ![Preguntas frecuentes](docs/screenshots/ayuda-preguntas-claro.png) | ![Contacto](docs/screenshots/contacto-formulario-claro.png) |
| ![Envíos y entregas](docs/screenshots/ayuda-envios-oscuro.png) | ![Pie en móvil](docs/screenshots/movil-pie-oscuro.png) |

Móvil: ![Móvil claro](docs/screenshots/movil-portada-claro.png) ![Móvil oscuro](docs/screenshots/movil-portada-oscuro.png)

## Estructura

```
app/Http/Controllers/TiendaController.php     tienda pública y búsqueda AJAX
app/Http/Controllers/CarritoController.php    pedido en sesión y confirmación
app/Http/Controllers/ContactoController.php   formulario de contacto (correo a la tienda)
app/Http/Controllers/Admin/*                  dashboard, clientes, facturas, pedidos, productos, categorías, perfiles
app/Support/Carrito.php, FacturaPdf.php       carrito en sesión y PDF con dompdf
app/Mail/FacturaCreadaMail.php                correo con PDF adjunto
app/Mail/MensajeContactoMail.php              mensaje del formulario de contacto
resources/views/tienda, admin, pdf, emails    vistas Blade
resources/css/app.css                         tokens de color y componentes (Tailwind 4)
docker-compose.yml, docker/php/               entorno local
```

No se versionan credenciales: `.env` está en `.gitignore` y `.env.example` solo trae valores de ejemplo.

---
Autor: **Jovany Morales del Ángel** · Licencia MIT
