<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Trait que proporciona las reglas de validación de contraseña reutilizables.
 */
trait PasswordValidationRules
{
    /**
     * Devuelve las reglas de validación para contraseñas.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
