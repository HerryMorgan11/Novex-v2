<?php

namespace App\Providers;

use App\Http\Middleware\InitializeTenancyFromUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Proveedor del módulo de inventario.
 *
 * Registra las rutas web del módulo protegidas con autenticación y tenancy.
 */
class ModuloInventarioServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios en el contenedor.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranca el módulo registrando sus rutas.
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
            InitializeTenancyFromUser::class,
        ])
            ->group(base_path('modulo-inventario/web.php'));
    }
}
