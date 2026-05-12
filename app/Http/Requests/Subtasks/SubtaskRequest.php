<?php

namespace App\Http\Requests\Subtasks;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Reglas de validación base compartidas para crear y actualizar subtareas.
 */
abstract class SubtaskRequest extends FormRequest
{
    /**
     * Autoriza la petición si hay un usuario autenticado.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Reglas de validación para una subtarea.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
