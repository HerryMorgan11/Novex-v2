<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Flujos de autenticación clásicos (login, registro, logout) para el guard web.
 *
 * La creación de usuario se delega en la Action CreatesNewUsers de Fortify para
 * compartir lógica con el registro automático tras login social.
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Muestra el formulario de registro.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Autentica al usuario con email y contraseña.
     * Usa session regeneration para prevenir session fixation.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Si el usuario tiene contraseña provisional, redirigir a cambio obligatorio
        if (Auth::user()->requiresPasswordChange()) {
            return redirect()->route('password.change-first-time');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Crea un nuevo usuario y lo autentica.
     * La lógica de creación se delega al CreatesNewUsers de Fortify.
     */
    public function store(Request $request, CreatesNewUsers $creator): RedirectResponse
    {
        $request->validate([
            'terms' => ['accepted'],
        ]);

        $user = $creator->create($request->all());

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        // Redirigir a /app donde el modal de crear empresa aparecerá si no tiene tenant
        return redirect('/app');
    }

    /**
     * Cierra la sesión del usuario y limpia los tokens de sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
