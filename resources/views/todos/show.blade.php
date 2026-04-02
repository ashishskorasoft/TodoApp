@extends('layouts.app', ['title' => 'Task Details | TodoFlow'])

@section('page_title', 'Task details')
@section('page_subtitle', 'Priority, reminders, recurrence, subtasks, and recent task notifications.')

@section('content')
<div class="app-card detail-hero section-space">
    <div class="detail-hero-head">
        <div>
            <h3 class="section-title mb-1">{{ $todo->title }}</h3>
            <p class="section-caption">Created {{ $todo->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <span class="chip {{ $todo->is_overdue ? 'chip-overdue' : 'chip-'.$todo->status }}">{{ $todo->is_overdue ? 'Overdue' : ucfirst($todo->status) }}</span>
    </div>

    <div class="info-grid">
        <div class="info-box"><div class="info-title">Priority</div><div class="info-value">{{ ucfirst($todo->priority) }}</div></div>
        <div class="info-box"><div class="info-title">Due at</div><div class="info-value">{{ $todo->due_at ? $todo->due_at->format('d M Y, h:i A') : 'No due date' }}</div></div>
        <div class="info-box"><div class="info-title">Reminder</div><div class="info-value">{{ $todo->reminder_minutes_before ? $todo->reminder_minutes_before.' min before' : 'None' }}</div></div>
        <div class="info-box"><div class="info-title">Repeats</div><div class="info-value">{{ $todo->has_recurrence ? ucfirst($todo->repeat_type).($todo->repeat_type === 'custom' ? ' every '.$todo->repeat_interval.' days' : '') : 'No repeat' }}</div></div>
    </div>

    @if(!empty($todo->labels))
        <div class="mini-tag-row mt-3">
            @foreach($todo->labels as $label)
                <span class="mini-tag active">{{ $label }}</span>
            @endforeach
        </div>
    @endif

    <div class="app-card section-card mt-3" style="background: var(--surface-2);">
        <div class="section-title mb-2">Notes</div>
        <p class="page-subtitle mb-0" style="font-size: 13px; color: var(--text);">{{ $todo->notes ?: 'No notes added yet.' }}</p>
    </div>

    <div class="app-card section-card mt-3">
        <div class="section-title mb-2">Subtasks</div>
        <div class="task-stack">
            @forelse($todo->checklistItems as $item)
                <div class="check-row"><span class="check-bullet {{ $item->is_completed ? 'done' : '' }}"></span><span>{{ $item->title }}</span></div>
            @empty
                <div class="empty-lite">No subtasks added.</div>
            @endforelse
        </div>
    </div>

    <div class="app-card section-card mt-3">
        <div class="section-title mb-2">Recent task notifications</div>
        <div class="task-stack">
            @forelse($todo->notifications->take(5) as $notification)
                <div class="activity-row"><div><div class="task-row-title">{{ $notification->title }}</div><div class="task-row-meta">{{ $notification->body }}</div></div><span class="dot {{ $notification->read_at ? 'is-read' : '' }}"></span></div>
            @empty
                <div class="empty-lite">No task notifications yet.</div>
            @endforelse
        </div>
    </div>

    <div class="detail-actions mt-3">
        <a href="{{ route('todos.edit', $todo) }}" class="btn btn-primary">Edit</a>
        <button type="button" class="btn btn-outline-brand" data-task-toggle data-url="{{ route('todos.quick-toggle', $todo) }}">Toggle</button>
        @if(!$todo->is_archived)
            <form method="POST" action="{{ route('todos.archive', $todo) }}">@csrf<button class="btn btn-soft">Archive</button></form>
        @else
            <form method="POST" action="{{ route('todos.restore', $todo) }}">@csrf<button class="btn btn-soft">Restore</button></form>
        @endif
        <a href="{{ route('todos.index') }}" class="btn btn-soft">Back</a>
    </div>
</div>
@endsection
