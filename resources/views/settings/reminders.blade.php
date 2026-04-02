@extends('layouts.app', ['title' => 'Reminder Settings | TodoFlow'])

@section('page_title', 'Reminder settings')
@section('page_subtitle', 'Control in-app, browser push, and optional email reminder behavior.')

@section('content')
<div class="app-card section-card section-space">
    <form method="POST" action="{{ route('settings.reminders.update') }}" data-loader="true" class="form-stack">
        @csrf
        @method('PATCH')

        <label class="switch-row"><span>In-app notifications</span><input type="checkbox" name="in_app_enabled" value="1" @checked($preferences->in_app_enabled)></label>
        <label class="switch-row"><span>Browser push notifications</span><input type="checkbox" name="push_enabled" value="1" @checked($preferences->push_enabled)></label>
        <label class="switch-row"><span>Email notifications (optional)</span><input type="checkbox" name="email_enabled" value="1" @checked($preferences->email_enabled)></label>
        <label class="switch-row"><span>Daily summary</span><input type="checkbox" name="daily_summary_enabled" value="1" @checked($preferences->daily_summary_enabled)></label>
        <label class="switch-row"><span>Due soon alerts</span><input type="checkbox" name="due_soon_enabled" value="1" @checked($preferences->due_soon_enabled)></label>
        <label class="switch-row"><span>Overdue alerts</span><input type="checkbox" name="overdue_enabled" value="1" @checked($preferences->overdue_enabled)></label>
        <label class="switch-row"><span>Recurring recreated alerts</span><input type="checkbox" name="recurring_recreated_enabled" value="1" @checked($preferences->recurring_recreated_enabled)></label>

        <div class="inline-grid two">
            <input type="time" name="daily_summary_time" class="form-input" value="{{ old('daily_summary_time', $preferences->daily_summary_time) }}">
            <input type="number" name="default_reminder_minutes" class="form-input" value="{{ old('default_reminder_minutes', $preferences->default_reminder_minutes) }}" min="0" max="10080" placeholder="Default reminder minutes">
        </div>

        <button class="btn btn-primary w-100">Save settings</button>
    </form>
</div>
@endsection
