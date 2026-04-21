<?php

namespace App\Http\Requests\Subtasks;

use Illuminate\Foundation\Http\FormRequest;

abstract class SubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
