<?php

namespace App\Http\Requests\ReminderLists;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Reglas de validación base compartidas para crear y actualizar listas de recordatorios.
 */
abstract class ReminderListRequest extends FormRequest
{
    /**
     * Autoriza la petición si hay un usuario autenticado.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Reglas de validación para una lista de recordatorios.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_default' => ['boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
