<?php

use App\Http\Controllers\Auth\GoogleController;
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
Route::post('/login', function () {
    return 'Login POST logic implementation needed';
});

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', function () {
    return 'Register POST logic implementation needed';
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    return 'Password Reset Link Sent';
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function () {
    return 'Password Reset Successful';
})->name('password.update');

Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function () {
    return 'Verification Email Sent';
})->name('verification.send');

Route::post('/logout', function () {
    return 'Logged out';
})->name('logout');

/*
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');
*/

// Dashboard central (pre-tenant)
Route::get('/app', function () {
    return view('dashboard.dashboard');
})->name('dashboard');

// Health checks
Route::get('/health', fn () => 'CENTRAL HEALTH OK');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');
