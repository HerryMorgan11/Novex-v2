<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central Routes
|--------------------------------------------------------------------------
|
| Rutas del dominio central (NO inicializan tenancy).
| Incluye: landing público, autenticación, registro de empresas.
|
*/

// Landing público
Route::get('/', function () {
    return view('landing.pages.home');
})->name('home');

Route::get('/pricing', function () {
    return view('landing.pages.pricing');
})->name('pricing');

// Autenticación (pre-tenant)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Health checks
Route::get('/health', fn () => 'CENTRAL HEALTH OK');
