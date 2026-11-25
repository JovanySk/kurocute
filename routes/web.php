<?php

use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacturaController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\PerfilController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TiendaController;
use Illuminate\Support\Facades\Route;

/*
| Tienda publica
*/
Route::get('/', [TiendaController::class, 'inicio'])->name('inicio');
Route::get('/catalogo', [TiendaController::class, 'catalogo'])->name('tienda.catalogo');
Route::get('/catalogo/buscar', [TiendaController::class, 'buscar'])->name('tienda.buscar');
Route::get('/producto/{producto:slug}', [TiendaController::class, 'producto'])->name('tienda.producto');

Route::prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/', [CarritoController::class, 'index'])->name('index');
    Route::get('/resumen', [CarritoController::class, 'resumen'])->name('resumen');
    Route::post('/', [CarritoController::class, 'agregar'])->name('agregar');
    Route::patch('/{producto}', [CarritoController::class, 'actualizar'])->name('actualizar');
    Route::delete('/{producto}', [CarritoController::class, 'quitar'])->name('quitar');
    Route::post('/confirmar', [CarritoController::class, 'confirmar'])->name('confirmar');
    Route::get('/gracias/{folio}', [CarritoController::class, 'gracias'])->name('gracias');
});

/*
| Ayuda y contacto
*/
Route::view('/preguntas-frecuentes', 'tienda.ayuda.preguntas')->name('ayuda.preguntas');
Route::view('/envios-y-entregas', 'tienda.ayuda.envios')->name('ayuda.envios');
Route::get('/contacto', [ContactoController::class, 'mostrar'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->middleware('throttle:5,1')->name('contacto.enviar');

/*
| Back office: lectura para cualquier usuario autenticado,
| escritura solo para el perfil Administrador (gate "administrar").
*/
Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware('can:administrar')->group(function () {
        Route::resource('clientes', ClienteController::class)->except(['index', 'show']);
        Route::resource('facturas', FacturaController::class)->except(['index', 'show']);
        Route::resource('productos', ProductoController::class)->except(['index', 'show']);
        Route::resource('perfiles', PerfilController::class)->except(['index', 'show'])->parameters(['perfiles' => 'perfil']);
        Route::resource('categorias', CategoriaController::class)->only(['store', 'update', 'destroy']);
        Route::patch('pedidos/{pedido}', [PedidoController::class, 'update'])->name('pedidos.update');
        Route::post('pedidos/{pedido}/facturar', [PedidoController::class, 'facturar'])->name('pedidos.facturar');
        Route::post('facturas/{factura}/reenviar', [FacturaController::class, 'reenviar'])->name('facturas.reenviar');
    });

    Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
    Route::get('facturas/{factura}/correo', [FacturaController::class, 'correo'])->name('facturas.correo');
    Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('perfiles', [PerfilController::class, 'index'])->name('perfiles.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
