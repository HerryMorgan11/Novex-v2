<?php

namespace App\Http\Requests\Reminders;

use App\Models\Reminder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Reglas de validación compartidas para crear y actualizar recordatorios.
 *
 * Se usa como base para StoreReminderRequest y UpdateReminderRequest para evitar
 * duplicar las reglas y mantener una única fuente de verdad.
 */
abstract class ReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'reminder_list_id' => [
                'nullable',
                'integer',
                Rule::exists('reminder_lists', 'id')->where('user_id', $userId),
            ],
            'notes' => ['nullable', 'string'],
            'priority' => ['nullable', 'integer', Rule::in([0, 1, 2, 3])],
            'starts_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'remind_at' => ['nullable', 'date'],
            'all_day' => ['boolean'],
            'status' => ['nullable', Rule::in([Reminder::STATUS_ACTIVE, Reminder::STATUS_ARCHIVED])],
        ];
    }
}
