<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\Tenant;
use App\Models\TenantMembership;
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

            // Ensure tenant exists for this user (existing social account case)
            if (! $user->current_tenant_id) {
                $tenant = Tenant::create([
                    'name' => $user->name.' Workspace',
                    'slug' => Str::slug($user->name).'-'.Str::lower(Str::random(6)),
                    'status' => 'provisioning',
                    'created_by_user_id' => $user->id,
                ]);

                TenantMembership::create([
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'is_owner' => true,
                    'status' => 'active',
                    'joined_at' => now(),
                ]);

                $user->forceFill(['current_tenant_id' => $tenant->id])->save();

                return redirect()->route('provisioning.page');
            }

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

        // Ensure tenant exists for this user. If none, create a provisioning tenant.
        if (! $user->current_tenant_id) {
            $tenant = Tenant::create([
                'name' => $user->name.' Workspace',
                'slug' => Str::slug($user->name).'-'.Str::lower(Str::random(6)),
                'status' => 'provisioning',
                'created_by_user_id' => $user->id,
            ]);

            TenantMembership::create([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'is_owner' => true,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $user->forceFill(['current_tenant_id' => $tenant->id])->save();
        }

        Auth::login($user);

        // If tenant is still provisioning, redirect to provisioning page
        $tenant = $user->currentTenant;
        if ($tenant && $tenant->status === 'provisioning') {
            return redirect()->route('provisioning.page');
        }

        return redirect()->intended('/app');
    }
}
