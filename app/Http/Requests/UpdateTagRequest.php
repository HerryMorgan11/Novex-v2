<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ignorar el propio tag al verificar unicidad
        $tagId = $this->route('tag')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tags')->where('user_id', auth()->id())->ignore($tagId),
            ],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }
}
