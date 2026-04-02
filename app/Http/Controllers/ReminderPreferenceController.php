<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReminderPreferenceRequest;
use App\Models\ReminderPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderPreferenceController extends Controller
{
    public function edit(Request $request): View
    {
        $preferences = $request->user()->reminderPreference ?: new ReminderPreference([
            'push_enabled' => true,
            'in_app_enabled' => true,
            'email_enabled' => false,
            'daily_summary_enabled' => true,
            'daily_summary_time' => '08:00',
            'default_reminder_minutes' => 30,
            'due_soon_enabled' => true,
            'overdue_enabled' => true,
            'recurring_recreated_enabled' => true,
        ]);

        return view('settings.reminders', compact('preferences'));
    }

    public function update(ReminderPreferenceRequest $request): RedirectResponse
    {
        $request->user()->reminderPreference()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'push_enabled' => (bool) $request->boolean('push_enabled'),
                'in_app_enabled' => (bool) $request->boolean('in_app_enabled'),
                'email_enabled' => (bool) $request->boolean('email_enabled'),
                'daily_summary_enabled' => (bool) $request->boolean('daily_summary_enabled'),
                'daily_summary_time' => $request->input('daily_summary_time'),
                'default_reminder_minutes' => $request->input('default_reminder_minutes', 30),
                'due_soon_enabled' => (bool) $request->boolean('due_soon_enabled'),
                'overdue_enabled' => (bool) $request->boolean('overdue_enabled'),
                'recurring_recreated_enabled' => (bool) $request->boolean('recurring_recreated_enabled'),
            ]
        );

        return back()->with('success', 'Reminder settings saved successfully.');
    }
}
