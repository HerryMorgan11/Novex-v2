<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReminderListController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TagController;
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

    // ──────────────────────────────────────────────────────────────────────
    // Reminders Module
    // ──────────────────────────────────────────────────────────────────────
    Route::middleware('initializeTenancyFromUser')->prefix('reminders')->name('reminders.')->group(function () {

        // ── Listas ──────────────────────────────────────────────────────
        Route::prefix('lists')->name('lists.')->group(function () {
            Route::get('/', [ReminderListController::class, 'index'])->name('index');
            Route::get('/create', [ReminderListController::class, 'create'])->name('create');
            Route::post('/', [ReminderListController::class, 'store'])->name('store');
            Route::get('/{reminderList}/edit', [ReminderListController::class, 'edit'])->name('edit');
            Route::put('/{reminderList}', [ReminderListController::class, 'update'])->name('update');
            Route::delete('/{reminderList}', [ReminderListController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [ReminderListController::class, 'reorder'])->name('reorder');
        });

        // ── Tags ────────────────────────────────────────────────────────
        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/', [TagController::class, 'index'])->name('index');
            Route::get('/create', [TagController::class, 'create'])->name('create');
            Route::post('/', [TagController::class, 'store'])->name('store');
            Route::get('/{tag}', [TagController::class, 'show'])->name('show');
            Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
            Route::put('/{tag}', [TagController::class, 'update'])->name('update');
            Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
        });

        // ── Recordatorios ───────────────────────────────────────────────
        Route::get('/', [ReminderController::class, 'index'])->name('index');
        Route::get('/create', [ReminderController::class, 'create'])->name('create');
        Route::post('/', [ReminderController::class, 'store'])->name('store');
        Route::get('/{reminder}', [ReminderController::class, 'show'])->name('show');
        Route::get('/{reminder}/edit', [ReminderController::class, 'edit'])->name('edit');
        Route::put('/{reminder}', [ReminderController::class, 'update'])->name('update');
        Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name('destroy');

        // Acciones de estado
        Route::patch('/{reminder}/complete', [ReminderController::class, 'complete'])->name('complete');
        Route::patch('/{reminder}/uncomplete', [ReminderController::class, 'uncomplete'])->name('uncomplete');
        Route::patch('/{reminder}/archive', [ReminderController::class, 'archive'])->name('archive');
        Route::patch('/{reminder}/unarchive', [ReminderController::class, 'unarchive'])->name('unarchive');
        Route::put('/{reminder}/move', [ReminderController::class, 'moveToList'])->name('move');
        Route::post('/{id}/restore', [ReminderController::class, 'restore'])->name('restore');
        Route::post('/reorder', [ReminderController::class, 'reorder'])->name('reorder');

        // ── Subtareas (nested bajo recordatorio) ────────────────────────
        Route::prefix('/{reminder}/subtasks')->name('subtasks.')->group(function () {
            Route::post('/', [SubtaskController::class, 'store'])->name('store');
            Route::put('/{subtask}', [SubtaskController::class, 'update'])->name('update');
            Route::delete('/{subtask}', [SubtaskController::class, 'destroy'])->name('destroy');
            Route::patch('/{subtask}/complete', [SubtaskController::class, 'complete'])->name('complete');
            Route::patch('/{subtask}/uncomplete', [SubtaskController::class, 'uncomplete'])->name('uncomplete');
            Route::post('/reorder', [SubtaskController::class, 'reorder'])->name('reorder');
        });
    });
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
