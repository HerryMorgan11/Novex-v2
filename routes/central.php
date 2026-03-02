<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
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
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

// Forgot password: show form and send reset link
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Fortify registers routes via its service provider; no explicit call needed here.

// If you prefer custom views, FortifyServiceProvider already points
// the views to the templates in resources/views/auth/*.blade.php
// so keeping the GET view routes is optional. Fortify handles them.

Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function () {
    return 'Verification Email Sent';
})->name('verification.send');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');
*/
Route::middleware(['auth', 'initializeTenant'])->group(function () {
    Route::get('/app', function () {
        $tenancyInitialized = null;
        $tenancyTenant = null;
        $tenancyError = null;

        if (function_exists('tenancy')) {
            $tenancyInitialized = tenancy()->initialized;
        }

        if (function_exists('tenant')) {
            try {
                $tenancyTenant = tenant();
            } catch (\Throwable $e) {
                $tenancyError = $e->getMessage();
            }
        } else {
            $tenancyError = 'function tenant not found';
        }

        return view('dashboard.dashboard', [
            'currentConnection' => DB::connection()->getName(),
            'currentDatabase' => DB::connection()->getDatabaseName(),
            'tenancyInitialized' => $tenancyInitialized,
            'tenancyTenant' => $tenancyTenant,
            'tenancyError' => $tenancyError,
        ]);
    })->name('dashboard');
});

// Provisioning page shown after registration while tenant is being prepared
Route::middleware('auth')->group(function () {
    Route::get('/provisioning', [App\Http\Controllers\ProvisioningController::class, 'page'])
        ->name('provisioning.page');

    Route::get('/provisioning/status', [App\Http\Controllers\ProvisioningController::class, 'status'])
        ->name('provisioning.status');
});
// Health checks
Route::get('/health', fn () => 'CENTRAL HEALTH OK');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');
