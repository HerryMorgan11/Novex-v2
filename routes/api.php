<?php

use App\Http\Controllers\Api\Inventario\ExpedicionApiController;
use App\Http\Controllers\Api\Inventario\TransporteApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Módulo de Inventario
|--------------------------------------------------------------------------
| Endpoints para integraciones externas (sistemas de transporte, clientes).
| Autenticados con Bearer token almacenado en api_tokens_inventario.
|
| Uso: POST /api/inventario/transportes
|      Authorization: Bearer {token}
*/

Route::middleware(['initializeTenancyFromApi', 'auth.api.inventario'])
    ->prefix('inventario')
    ->name('api.inventario.')
    ->group(function () {

        // Registrar transporte de entrada desde sistema externo
        Route::post('/transportes', [TransporteApiController::class, 'store'])
            ->name('transportes.store');

        // Confirmar entrega de expedición
        Route::post('/expediciones/{referencia}/confirmar-entrega', [ExpedicionApiController::class, 'confirmarEntrega'])
            ->name('expediciones.confirmar');
    });
