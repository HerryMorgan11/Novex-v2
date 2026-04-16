<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuloInventarioServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar rutas
        $this->registerRoutes();
    }

    /**
     * Registra las rutas del módulo.
     */
    protected function registerRoutes(): void
    {
        Route::middleware([
            'web',
            'auth',
            \App\Http\Middleware\InitializeTenancyFromUser::class,
        ])
            ->group(base_path('modulo-inventario/web.php'));
    }
}
