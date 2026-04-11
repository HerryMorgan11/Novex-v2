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

Route::get('/precios', function () {
    return view('landing.pages.pricing');
})->name('precios');

Route::get('/about', function () {
    return view('landing.pages.about');
})->name('about');

Route::get('/contabilidad', function () {
    return view('landing.pages.contabilidad');
})->name('contabilidad');

Route::get('/inventario', function () {
    return view('landing.pages.inventario');
})->name('inventario');

Route::get('/crm', function () {
    return view('landing.pages.crm');
})->name('crm');

Route::get('/recursos-humanos', function () {
    return view('landing.pages.recursos-humanos');
})->name('recursos-humanos');

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
Route::middleware(['auth', 'checkHasTenant'])->group(function () {
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

        return view('dashboard.app.dashboard', [
            'currentConnection' => DB::connection()->getName(),
            'currentDatabase' => DB::connection()->getDatabaseName(),
            'tenancyInitialized' => $tenancyInitialized,
            'tenancyTenant' => $tenancyTenant,
            'tenancyError' => $tenancyError,
        ]);
    })->name('dashboard');

    // Route::livewire('/settings/profile', 'settings.profile')->name("settings.profile");
    Route::get('/settings/profile', function () {
        return view('dashboard.features.settings.settingsApp');
    })->name('settings.profile');

    Route::get('/controlpanel/home', function () {
        return view('dashboard.features.control-panel.controlPanelApp');
    })->name('controlpanel.home');

    // Módulo de Notas
    Route::get('/dashboard/features/notes', [App\Http\Controllers\Dashboard\Features\NoteController::class, 'index'])->name('dashboard.features.notes.index');
    Route::get('/dashboard/features/notes/create', [App\Http\Controllers\Dashboard\Features\NoteController::class, 'create'])->name('dashboard.features.notes.create');
    Route::post('/dashboard/features/notes', [App\Http\Controllers\Dashboard\Features\NoteController::class, 'store'])->name('dashboard.features.notes.store');
    Route::get('/dashboard/features/notes/{id}/edit', [App\Http\Controllers\Dashboard\Features\NoteController::class, 'edit'])->name('dashboard.features.notes.edit');
    Route::post('/dashboard/features/notes/{id}', [App\Http\Controllers\Dashboard\Features\NoteController::class, 'update'])->name('dashboard.features.notes.update');
    Route::post('/dashboard/features/notes/{note}/delete', [App\Http\Controllers\Dashboard\Features\NoteController::class, 'destroy'])->name('dashboard.features.notes.destroy');
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
