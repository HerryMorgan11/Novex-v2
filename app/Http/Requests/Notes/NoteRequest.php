<?php

namespace App\Http\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Reglas de validación base compartidas para crear y actualizar notas.
 */
abstract class NoteRequest extends FormRequest
{
    /**
     * Autoriza la petición si hay un usuario autenticado.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Reglas de validación para una nota.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ];
    }
}
