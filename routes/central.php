<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*

| Central Routes
| Rutas del dominio central (NO inicializan tenancy).
| Incluye: landing público, autenticación, registro de empresas.
*/

// Rutas de Interfaz de Presentación (Landing Pages)
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

// Controladores de Autenticación Unificada (Identity Management / Pre-Tenant)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

// Protocolos de Recuperación de Datos de Acceso (Vista form y envío de link de restablecimiento)
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

// La gestión integral de rutas avanzadas se delega estructuralmente al FortifyServiceProvider.
// Toda asociación MVC de autenticación está mapeada implícitamente sin necesidad explícita en este index.

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
    Route::get('/calendario', function () {
        return view('dashboard.features.calendario.calendario');
    })->name('calendario');

});

// Etapas transitorias post-registro para aprovisionamiento dinámico de base de datos multitenancy
Route::middleware('auth')->group(function () {
    Route::get('/provisioning', [App\Http\Controllers\ProvisioningController::class, 'page'])
        ->name('provisioning.page');

    Route::get('/provisioning/status', [App\Http\Controllers\ProvisioningController::class, 'status'])
        ->name('provisioning.status');
});
// Endpoints de comprobación de integridad y métricas del sistema (Health Checks API)
Route::get('/health', fn () => 'CENTRAL HEALTH OK');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');
