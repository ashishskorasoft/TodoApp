@extends('layouts.app', ['title' => 'Admin Settings | TodoFlow'])

@section('page_title', 'Admin Settings')
@section('page_subtitle', 'Control branding and summary defaults for the workspace.')

@section('content')
<div class="app-card section-card section-space">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="form-stack">
        @csrf
        @method('PATCH')

        <div class="input-shell">
            <label class="field-label">App Name</label>
            <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" class="form-control" required>
            @error('app_name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Support Email</label>
            <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" class="form-control">
            @error('support_email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Theme Color</label>
            <input type="text" name="theme_color" value="{{ old('theme_color', $settings['theme_color']) }}" class="form-control" required>
            @error('theme_color')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Daily Summary Time</label>
            <input type="text" name="daily_summary_time" value="{{ old('daily_summary_time', $settings['daily_summary_time']) }}" class="form-control" placeholder="08:00 AM" required>
            @error('daily_summary_time')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="task-form-actions">
            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Save Settings</button>
        </div>
    </form>
</div>
@endsection
