<?php

namespace App\Http\Requests\ReminderLists;

use Illuminate\Foundation\Http\FormRequest;

abstract class ReminderListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

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
