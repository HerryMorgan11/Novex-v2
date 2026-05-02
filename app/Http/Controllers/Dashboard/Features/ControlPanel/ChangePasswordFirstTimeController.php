<?php

namespace App\Http\Controllers\Dashboard\Features\ControlPanel;

use App\Http\Controllers\Controller;
use App\Models\TenantMembership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Gestiona el flujo de cambio de contraseña obligatorio en el primer inicio de sesión.
 *
 * Se activa cuando la membresía del usuario tiene status 'pending'.
 * Tras el cambio, la membresía pasa a 'active' y el usuario accede al dashboard.
 */
class ChangePasswordFirstTimeController extends Controller
{
    /**
     * Muestra el formulario de cambio de contraseña obligatorio.
     */
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user->requiresPasswordChange()) {
            return redirect()->route('dashboard');
        }

        return view('auth.change-password-first-time');
    }

    /**
     * Procesa el cambio de contraseña y activa la membresía del usuario.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->requiresPasswordChange()) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
        ], [
            'password.different' => 'La nueva contraseña no puede ser igual a la contraseña provisional.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'current_password.required' => 'Introduce tu contraseña provisional.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña provisional introducida es incorrecta.',
            ]);
        }

        // Actualizar contraseña
        $user->password = Hash::make($request->password);
        $user->setConnection('central');
        $user->save();

        // Activar membresía
        TenantMembership::on('central')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'invited'])
            ->update([
                'status' => 'active',
                'joined_at' => now(),
            ]);

        return redirect()->route('dashboard')
            ->with('success', '¡Contraseña actualizada! Bienvenido a Novex.');
    }
}
