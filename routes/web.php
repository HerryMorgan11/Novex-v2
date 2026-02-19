<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (DEPRECATED)
|--------------------------------------------------------------------------
|
| Este archivo ya NO se usa en el sistema multi-tenant.
| Las rutas están divididas en:
|   - routes/central.php → Landing, auth, registro de empresas
|   - routes/tenant.php  → Dashboard y funcionalidad de cada tenant
|
| Si ves este mensaje, significa que bootstrap/app.php aún registra web.php.
| Debería registrar central.php en su lugar.
|
*/

Route::get('/', fn () => 'DEPRECATED: Use routes/central.php or routes/tenant.php');
