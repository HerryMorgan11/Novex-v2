<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ControlPanelController;
use App\Http\Controllers\Dashboard\Features\NoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProvisioningController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReminderListController;
use App\Http\Controllers\SubtaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central Routes
|--------------------------------------------------------------------------
| Rutas del dominio central (NO inicializan tenancy).
| Incluye: landing público, autenticación, registro de empresas.
|
| NOTA: Las rutas del módulo de recordatorios están aquí temporalmente
| por compatibilidad con el middleware InitializeTenancyFromUser.
| En una futura refactorización se moverán a tenant.php.
*/

// ──────────────────────────────────────────────────────────────────────────────
// Landing Pages (acceso público, sin autenticación)
// ──────────────────────────────────────────────────────────────────────────────

Route::get('/', fn () => view('landing.pages.home'))->name('home');
Route::get('/precios', fn () => view('landing.pages.pricing'))->name('precios');
Route::get('/about', fn () => view('landing.pages.about'))->name('about');
Route::get('/contabilidad', fn () => view('landing.pages.contabilidad'))->name('contabilidad');
Route::get('/inventario', fn () => view('landing.pages.inventario'))->name('inventario');
Route::get('/crm', fn () => view('landing.pages.crm'))->name('crm');
Route::get('/recursos-humanos', fn () => view('landing.pages.recursos-humanos'))->name('recursos-humanos');

// ──────────────────────────────────────────────────────────────────────────────
// Autenticación (login, registro, recuperación de contraseña)
// ──────────────────────────────────────────────────────────────────────────────

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Recuperación de contraseña por email
Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Verificación de email
Route::get('/verify-email', fn () => view('auth.verify-email'))->name('verification.notice');
Route::post('/email/verification-notification', fn () => 'Verification Email Sent')->name('verification.send');

// OAuth Google
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// ──────────────────────────────────────────────────────────────────────────────
// Área de la aplicación (requiere autenticación y tenant activo)
// ──────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'checkHasTenant'])->group(function () {

    // Dashboard principal
    Route::get('/app', [DashboardController::class, 'index'])->name('dashboard');

    // Ajustes del perfil
    Route::get('/settings/profile', fn () => view('dashboard.features.settings.settingsApp'))
        ->name('settings.profile');

    // Panel de control (carga usuarios del tenant para la vista)
    Route::get('/controlpanel/home', [ControlPanelController::class, 'index'])
        ->name('controlpanel.home');

    // Creación de empresa (reemplaza Livewire CreateCompanyModal)
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');

    // Calendario
    Route::get('/calendario', fn () => view('dashboard.features.calendario.calendario'))
        ->name('calendario');

    // ── Módulo de Notas ──────────────────────────────────────────────────────
    Route::prefix('dashboard/features/notes')->name('dashboard.features.notes.')->group(function () {
        Route::get('/', [NoteController::class, 'index'])->name('index');
        Route::get('/create', [NoteController::class, 'create'])->name('create');
        Route::post('/', [NoteController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [NoteController::class, 'edit'])->name('edit');
        Route::post('/{id}', [NoteController::class, 'update'])->name('update');
        Route::post('/{note}/delete', [NoteController::class, 'destroy'])->name('destroy');
    });

    // ── Módulo de Recordatorios ──────────────────────────────────────────────
    // El middleware initializeTenancyFromUser conecta al tenant del usuario autenticado.
    Route::middleware('initializeTenancyFromUser')->prefix('reminders')->name('reminders.')->group(function () {

        // Listas de recordatorios (CRUD + reordenamiento)
        Route::prefix('lists')->name('lists.')->group(function () {
            Route::get('/', [ReminderListController::class, 'index'])->name('index');
            Route::get('/create', [ReminderListController::class, 'create'])->name('create');
            Route::post('/', [ReminderListController::class, 'store'])->name('store');
            Route::get('/{reminderList}/edit', [ReminderListController::class, 'edit'])->name('edit');
            Route::put('/{reminderList}', [ReminderListController::class, 'update'])->name('update');
            Route::delete('/{reminderList}', [ReminderListController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [ReminderListController::class, 'reorder'])->name('reorder');
        });

        // Recordatorios (CRUD + acciones de estado + reordenamiento)
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

        // Subtareas (anidadas bajo un recordatorio)
        Route::prefix('/{reminder}/subtasks')->name('subtasks.')->group(function () {
            Route::post('/', [SubtaskController::class, 'store'])->name('store');
            Route::put('/{subtask}', [SubtaskController::class, 'update'])->name('update');
            Route::delete('/{subtask}', [SubtaskController::class, 'destroy'])->name('destroy');
            Route::patch('/{subtask}/complete', [SubtaskController::class, 'complete'])->name('complete');
            Route::patch('/{subtask}/uncomplete', [SubtaskController::class, 'uncomplete'])->name('uncomplete');
            Route::post('/reorder', [SubtaskController::class, 'reorder'])->name('reorder');
        });
    });
});

// ──────────────────────────────────────────────────────────────────────────────
// Provisioning (post-registro, asignación de base de datos al tenant)
// ──────────────────────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/provisioning', [ProvisioningController::class, 'page'])
        ->name('provisioning.page');
    Route::get('/provisioning/status', [ProvisioningController::class, 'status'])
        ->name('provisioning.status');
});

// Health check de la aplicación central
Route::get('/health', fn () => 'CENTRAL HEALTH OK');
