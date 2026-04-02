<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:pending,completed'],
            'due_at' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'due_hour' => ['nullable', 'string'],
            'due_minute' => ['nullable', 'string'],
            'due_period' => ['nullable', 'in:AM,PM'],
            'labels' => ['nullable', 'string', 'max:255'],
            'reminder_minutes_before' => ['nullable', 'integer', 'min:0', 'max:10080'],
            'repeat_type' => ['nullable', 'in:daily,weekly,monthly,custom'],
            'repeat_interval' => ['nullable', 'integer', 'min:1', 'max:365'],
            'repeat_weekdays' => ['nullable', 'array'],
            'repeat_weekdays.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'subtasks' => ['nullable', 'array'],
            'subtasks.*' => ['nullable', 'string', 'max:120'],
        ];
    }
}
