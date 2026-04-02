@extends('layouts.app', ['title' => 'Calendar | TodoFlow'])

@section('page_title', 'Calendar')
@section('page_subtitle', 'Plan tasks by day with a clean, app-style monthly calendar.')

@section('content')
<div class="app-card section-card section-space">
    <div class="calendar-shell">
        <div class="calendar-toolbar">
            <a class="mini-link" href="{{ route('calendar.index', ['month' => $anchor->copy()->subMonth()->format('Y-m')]) }}"><i class="bi bi-chevron-left"></i><span>Prev</span></a>
            <div class="calendar-title-wrap">
                <h3 class="section-title mb-0"><i class="bi bi-calendar3 me-2 text-brand"></i>{{ $anchor->format('F Y') }}</h3>
                <p class="section-caption mb-0">Tap a task badge to open details.</p>
            </div>
            <a class="mini-link" href="{{ route('calendar.index', ['month' => $anchor->copy()->addMonth()->format('Y-m')]) }}"><span>Next</span><i class="bi bi-chevron-right"></i></a>
        </div>

        <div class="calendar-legend">
            <span><i class="bi bi-circle-fill legend-dot legend-today"></i> Today</span>
            <span><i class="bi bi-circle-fill legend-dot legend-due"></i> Due</span>
            <span><i class="bi bi-circle-fill legend-dot legend-done"></i> Completed</span>
            <span><i class="bi bi-circle-fill legend-dot legend-overdue"></i> Overdue</span>
        </div>

        <div class="calendar-grid month-grid">
            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)
                <div class="weekday-head">{{ $weekday }}</div>
            @endforeach
            @foreach($days as $day)
                <div class="month-day {{ !$day['in_month'] ? 'muted' : '' }} {{ $day['date']->isToday() ? 'today' : '' }}">
                    <div class="month-day-top">
                        <span class="month-day-number">{{ $day['date']->format('j') }}</span>
                        @if($day['tasks']->count())
                            <span class="day-count">{{ $day['tasks']->count() }}</span>
                        @endif
                    </div>
                    <div class="month-day-stack">
                        @forelse($day['tasks']->take(3) as $task)
                            <a href="{{ route('todos.show', $task) }}" class="month-task {{ $task->status === 'completed' ? 'done' : ($task->is_overdue ? 'overdue' : '') }}">
                                <span class="month-task-dot"></span>
                                <span>{{ \Illuminate\Support\Str::limit($task->title, 16) }}</span>
                            </a>
                        @empty
                            <span class="month-empty">No tasks</span>
                        @endforelse
                        @if($day['tasks']->count() > 3)
                            <span class="more-badge">+{{ $day['tasks']->count() - 3 }} more</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
