<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SeccionController;
use Illuminate\Support\Facades\Route;
use App\Models\Producto;
use Illuminate\Http\Request;

// Página principal: pública
Route::get('/', function (Request $request) {
    $q = $request->input('q');
    $productos = Producto::when($q, function ($query, $q) {
        return $query->where('nombre', 'like', "%$q%");
    })->get();
    return view('secciones.index', compact('productos'));
});

// Dashboard: protegida
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Agrupa las rutas protegidas SOLO con 'auth'
Route::middleware(['auth'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('facturas', FacturaController::class);
    Route::resource('perfiles', PerfilController::class);
    Route::resource('secciones', SeccionController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';