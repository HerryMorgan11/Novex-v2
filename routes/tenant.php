<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Rutas del dominio tenant (inicializan tenancy automáticamente).
| Middlewares aplicados automáticamente por TenancyServiceProvider:
|   - PreventAccessFromCentralDomains
|   - InitializeTenancyByDomain
|
| NO agregues middlewares de tenancy aquí, ya están en el provider.
|
*/

Route::get('/health', fn () => 'TENANT HEALTH OK');
