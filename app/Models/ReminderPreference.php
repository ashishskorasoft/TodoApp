<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'push_enabled',
        'in_app_enabled',
        'email_enabled',
        'daily_summary_enabled',
        'daily_summary_time',
        'default_reminder_minutes',
        'due_soon_enabled',
        'overdue_enabled',
        'recurring_recreated_enabled',
    ];

    protected function casts(): array
    {
        return [
            'push_enabled' => 'boolean',
            'in_app_enabled' => 'boolean',
            'email_enabled' => 'boolean',
            'daily_summary_enabled' => 'boolean',
            'due_soon_enabled' => 'boolean',
            'overdue_enabled' => 'boolean',
            'recurring_recreated_enabled' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
