<?php

namespace App\Http\Controllers\Dashboard\Features\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

/**
 * Ajustes de cuenta del usuario (perfil + seguridad).
 * Delega la lógica real a las Actions de Fortify para mantener una única fuente
 * de verdad con el flujo estándar de Laravel Jetstream/Fortify.
 */
class SettingsController extends Controller
{
    public function show(): View
    {
        return view('dashboard.features.settings.settingsApp');
    }

    public function updateProfile(Request $request, UpdatesUserProfileInformation $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->only(['name', 'email']));

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request, UpdatesUserPasswords $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->only([
            'current_password', 'password', 'password_confirmation',
        ]));

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
