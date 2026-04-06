<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Facades\Tenancy;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Rutas del dominio tenant (inicializan tenancy automáticamente).
| Middlewares aplicados automáticamente por TenancyServiceProvider:
|   - PreventAccessFromCentralDomains
|   - InitializeTenancyByDomain (o BySubdomain según configuración)
|
| NO agregues middlewares de tenancy aquí, ya están en el provider.
|
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard del tenant (evita colisión con "/" del dominio central)
    Route::get('/dashboard', function () {
        return 'Tenant: '.tenant('id').' | Database: '.DB::connection()->getDatabaseName();
    })->name('tenant.dashboard');

    // Endpoint de debug (eliminar en producción)
    Route::get('/__tenancy', function () {
        return [
            'host' => request()->getHost(),
            'tenant_id' => optional(Tenancy::getTenant())->id,
            'connection' => DB::connection()->getName(),
            'database' => DB::connection()->getDatabaseName(),
        ];
    });

    // ──────────────────────────────────────────────────────────────────────
    // Other tenant routes can go here
    // ──────────────────────────────────────────────────────────────────────
});

Route::get('/health', fn () => 'TENANT HEALTH OK');
