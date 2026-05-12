<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Acción de Fortify para registrar un nuevo usuario.
 *
 * Crea el usuario sin tenant; el onboarding de empresa se realiza
 * después del primer inicio de sesión mediante un modal.
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida los datos de entrada y crea el usuario.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Create user WITHOUT tenant - they will create/select tenant on first login via modal
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'current_tenant_id' => null,
        ]);

        return $user->fresh();
    }
}
