# KuroCute — Laravel store & invoicing

A Laravel 12 web app for a small themed store: a public product catalog with search, plus an authenticated back office for **clients, invoices, profiles and catalog sections**.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20) ![PHP](https://img.shields.io/badge/PHP-8.2-777BB4) ![MySQL](https://img.shields.io/badge/MySQL-Eloquent-4479A1) ![Breeze](https://img.shields.io/badge/auth-Laravel%20Breeze-blue)

## Features

- **Public catalog:** home page with product search (`?q=`) using Eloquent `LIKE` queries.
- **Authentication:** login, registration, email verification, password reset and profile management (Laravel Breeze).
- **Clients:** CRUD with profile image upload to the `public` disk.
- **Invoices:**
  - CRUD with payment method and invoice status catalogs;
  - **PDF/XML attachment** upload (validated, max 10 MB);
  - an **automatic email to the client** when an invoice is created (`FacturaCreadaMail`).
- **Data model:** 8 Eloquent models (Cliente, Factura, Pedido, Producto, Perfil, FormasPago, EstadoFactura, User) and 10 migrations with foreign keys.
- **UI:** Blade, Bootstrap 5, Tailwind, Alpine.js, AJAX modals and CKEditor for invoice notes.
- **Tests:** 25 PHPUnit feature tests for registration, login, email verification, password reset and profile flows (SQLite in-memory).

## Run it

```bash
cp .env.example .env          # set DB_* and MAIL_* values
composer install && npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build && php artisan serve
php artisan test
```

No credentials are committed. Uploaded client images and invoices are stored under `storage/app` and are not versioned.

---
Author: **Jovany Morales del Ángel** · MIT License
