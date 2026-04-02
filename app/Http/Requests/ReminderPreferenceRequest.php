<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReminderPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'push_enabled' => ['nullable', 'boolean'],
            'in_app_enabled' => ['nullable', 'boolean'],
            'email_enabled' => ['nullable', 'boolean'],
            'daily_summary_enabled' => ['nullable', 'boolean'],
            'daily_summary_time' => ['nullable', 'date_format:H:i'],
            'default_reminder_minutes' => ['nullable', 'integer', 'min:0', 'max:10080'],
            'due_soon_enabled' => ['nullable', 'boolean'],
            'overdue_enabled' => ['nullable', 'boolean'],
            'recurring_recreated_enabled' => ['nullable', 'boolean'],
        ];
    }
}
