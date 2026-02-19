<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
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
            Auth::login($socialAccount->user);

            return redirect()->intended('/app');
        }

        // 2️⃣ Si no existe, buscar usuario por email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(32)),
            ]);
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

        return redirect()->intended('/app');
    }
}
