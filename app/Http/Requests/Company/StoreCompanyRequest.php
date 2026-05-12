<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de una nueva empresa (tenant).
 */
class StoreCompanyRequest extends FormRequest
{
    /**
     * Autoriza la petición si hay un usuario autenticado.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Reglas de validación para crear una empresa.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
        ];
    }
}
