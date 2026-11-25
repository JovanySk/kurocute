<?php

namespace App\Providers;

use App\Models\Categoria;
use App\Models\User;
use App\Support\Carrito;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Carrito::class, fn ($app) => new Carrito($app['session.store']));
    }

    public function boot(): void
    {
        Carbon::setLocale('es');

        // Solo el perfil Administrador puede crear, editar o eliminar registros.
        Gate::define('administrar', fn (User $user) => $user->esAdmin());

        Paginator::defaultView('components.paginacion');

        View::composer('components.layouts.tienda', function ($view) {
            $view->with('carritoCantidad', app(Carrito::class)->cantidad());
            // Categorías reales para los enlaces del pie de página.
            $view->with('categoriasPie', Categoria::orderBy('id')->get());
        });
    }
}
