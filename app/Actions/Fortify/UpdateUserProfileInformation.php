<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

/**
 * Acción de Fortify para actualizar la información de perfil del usuario.
 *
 * Gestiona también la re-verificación de email si cambia la dirección.
 */
class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Valida y actualiza nombre, email, teléfono y DNI del usuario.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('mysql.users')->ignore($user->id),
            ],

            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[+\d\s\-()\/.]+$/'],
            'dni' => ['nullable', 'string', 'max:15'],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'] ?? null,
                'dni' => $input['dni'] ?? null,
            ])->save();
        }
    }

    /**
     * Actualiza el perfil e invalida la verificación de email.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'phone' => $input['phone'] ?? null,
            'dni' => $input['dni'] ?? null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
