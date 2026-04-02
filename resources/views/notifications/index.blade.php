@extends('layouts.app', ['title' => 'Notifications | TodoFlow'])

@section('page_title', 'Notifications')
@section('page_subtitle', 'In-app alerts plus browser notifications while the app is open on this device.')

@section('content')
<div class="app-card section-card section-space">
    <div class="section-head" style="gap:12px; align-items:flex-start;">
        <div>
            <h3 class="section-title">Browser notifications</h3>
            <p class="section-caption">Enable browser reminders for due soon, overdue, recurring, and daily summary alerts.</p>
            <p class="section-caption mb-0">Push setting: <strong>{{ $pushEnabled ? 'On' : 'Off' }}</strong> · Active devices: <strong>{{ $activeDevices }}</strong></p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button type="button" class="btn btn-primary" id="enablePushBtn">Enable push</button>
            <form method="POST" action="{{ route('notifications.test') }}">@csrf<button type="submit" class="btn btn-light">Send test</button></form>
        </div>
    </div>
</div>

<div class="app-card section-card section-space">
    <div class="task-stack">
        @forelse($notifications as $notification)
            <div class="activity-row align-start">
                <div>
                    <div class="task-row-title">{{ $notification->title }}</div>
                    <div class="task-row-meta">{{ $notification->body }}</div>
                    <div class="section-caption mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf<button class="mini-link">Mark read</button></form>
                @endif
            </div>
        @empty
            <div class="empty-lite">No notifications yet. Use “Send test” or run your reminder scheduler to generate due soon, overdue, recurring, and daily summary alerts.</div>
        @endforelse
    </div>
    <div class="pt-3">{{ $notifications->links() }}</div>
</div>
@endsection
