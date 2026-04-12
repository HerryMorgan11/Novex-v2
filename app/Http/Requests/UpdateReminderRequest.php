<?php

namespace App\Http\Requests;

use App\Models\Reminder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'reminder_list_id' => [
                'nullable',
                'integer',
                Rule::exists('reminder_lists', 'id')->where('user_id', auth()->id()),
            ],
            'notes' => ['nullable', 'string'],
            'priority' => ['nullable', 'integer', Rule::in([0, 1, 2, 3])],
            'starts_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'remind_at' => ['nullable', 'date'],
            'all_day' => ['boolean'],
            'status' => ['nullable', Rule::in([Reminder::STATUS_ACTIVE, Reminder::STATUS_ARCHIVED])],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => [
                'integer',
                Rule::exists('tags', 'id')->where('user_id', auth()->id()),
            ],
        ];
    }
}
