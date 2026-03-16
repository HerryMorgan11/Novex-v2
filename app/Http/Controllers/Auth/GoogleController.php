<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Error attempting to login with Google.']);
        }

        // 1️⃣ Buscar si ya existe esa cuenta social
        $socialAccount = SocialAccount::where('provider', 'google')
            ->where('provider_user_id', $googleUser->getId())
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;
            Auth::login($user);

            // Si el usuario ya tiene un tenant, redirigir a /app
            if ($user->current_tenant_id) {
                return redirect()->intended('/app');
            }

            // Si no tiene tenant, redirigir a /app para que vea el modal
            return redirect()->intended('/app');
        }

        // 2️⃣ Si no existe, buscar usuario por email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            // Crear usuario SIN tenant (igual que en CreateNewUser)
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(32)),
                'current_tenant_id' => null,  // NO crear tenant automáticamente
            ]);

            // Fire Registered event for consistency
            event(new Registered($user));
        }

        // 3️⃣ Crear relación social
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => $googleUser->getId(),
            'email' => $googleUser->getEmail(),
            'access_token' => $googleUser->token,
            'refresh_token' => $googleUser->refreshToken,
            'token_expires_at' => isset($googleUser->expiresIn) ? now()->addSeconds($googleUser->expiresIn) : null,
        ]);

        Auth::login($user);

        // Redirigir a /app (donde el middleware checkHasTenant mostrará el modal)
        return redirect()->intended('/app');
    }
}
