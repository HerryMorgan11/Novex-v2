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

// Dashboard del tenant
Route::get('/', function () {
    return 'Tenant: '.tenant('id').' | Database: '.DB::connection()->getDatabaseName();
})->name('tenant.dashboard');

Route::get('/health', fn () => 'TENANT HEALTH OK');

// Endpoint de debug (eliminar en producción)
Route::get('/__tenancy', function () {
    return [
        'host' => request()->getHost(),
        'tenant_id' => optional(Tenancy::getTenant())->id,
        'connection' => DB::connection()->getName(),
        'database' => DB::connection()->getDatabaseName(),
    ];
});
