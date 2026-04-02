@extends('layouts.app', ['title' => 'Dashboard | TodoFlow'])

@section('page_title', 'Home')
@section('page_subtitle', 'Track today, upcoming work, and your progress in one app-like dashboard.')

@section('content')
<div class="metric-grid section-space">
    <div class="app-card metric-card">
        <div class="metric-top"><span class="metric-label">Total</span><span class="metric-icon">TL</span></div>
        <div class="metric-value">{{ $total }}</div>
    </div>
    <div class="app-card metric-card">
        <div class="metric-top"><span class="metric-label">Pending</span><span class="metric-icon">PN</span></div>
        <div class="metric-value">{{ $pending }}</div>
    </div>
    <div class="app-card metric-card">
        <div class="metric-top"><span class="metric-label">Done</span><span class="metric-icon">DN</span></div>
        <div class="metric-value">{{ $completed }}</div>
    </div>
    <div class="app-card metric-card">
        <div class="metric-top"><span class="metric-label">Overdue</span><span class="metric-icon">OD</span></div>
        <div class="metric-value">{{ $overdue }}</div>
    </div>
    <div class="app-card metric-card">
        <div class="metric-top"><span class="metric-label">Today</span><span class="metric-icon">TD</span></div>
        <div class="metric-value">{{ $todayTasks->count() }}</div>
    </div>
    <div class="app-card metric-card metric-card-accent">
        <div class="metric-top"><span class="metric-label">Progress</span><span class="metric-icon">PG</span></div>
        <div class="metric-value">{{ $completionRate }}%</div>
    </div>
</div>

<div class="app-card section-card section-space dashboard-highlight-card">
    <div class="section-header">
        <div>
            <h3 class="section-title">Focus Snapshot</h3>
            <p class="section-caption">Live dashboard guidance based on your current task state.</p>
        </div>
        <span class="focus-badge">{{ $todayTasks->count() }} today</span>
    </div>
    <div class="dashboard-highlight-grid">
        <div>
            <h4 class="dashboard-highlight-title">{{ $focusMessage }}</h4>
            <p class="dashboard-highlight-copy">Completion rate is <strong>{{ $completionRate }}%</strong> with <strong>{{ $pending }}</strong> pending and <strong>{{ $overdue }}</strong> overdue tasks in the active workspace.</p>
        </div>
        <div class="dashboard-highlight-meta">
            <div class="highlight-stat">
                <span>Unread reminders</span>
                <strong>{{ $notifications->whereNull('read_at')->count() }}</strong>
            </div>
            <div class="highlight-stat">
                <span>Upcoming</span>
                <strong>{{ $upcomingTasks->count() }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="desktop-two section-space">
    <div class="app-card section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Today's Tasks</h3>
                <p class="section-caption">Open and finish the tasks due today.</p>
            </div>
            <a href="{{ route('todos.create') }}" class="btn btn-primary">+ Add</a>
        </div>

        @if($todayTasks->count())
            <div class="task-stack">
                @foreach($todayTasks as $task)
                    <a href="{{ route('todos.show', $task) }}" class="task-card-link app-card">
                        <div class="task-card">
                            <div class="task-toggle {{ $task->status === 'completed' ? 'done' : '' }}">{{ $task->status === 'completed' ? '✓' : strtoupper(substr($task->title, 0, 1)) }}</div>
                            <div class="task-content">
                                <div class="task-title-row"><h4 class="task-title">{{ $task->title }}</h4></div>
                                <p class="task-time">{{ $task->due_at ? $task->due_at->format('d M, h:i A') : 'No due time' }}</p>
                                <div class="task-chips">
                                    <span class="chip chip-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                    <span class="chip {{ $task->is_overdue ? 'chip-overdue' : 'chip-'.$task->status }}">{{ $task->is_overdue ? 'Overdue' : ucfirst($task->status) }}</span>
                                </div>
                            </div>
                            <div class="chevron">›</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">✓</div>
                <h4 class="empty-title">Nothing due today</h4>
                <p class="empty-copy">You are clear for now. Add a task to stay ahead.</p>
            </div>
        @endif
    </div>

    <div class="task-stack">
        <div class="app-card section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">Quick Actions</h3>
                    <p class="section-caption">Move faster with app-like shortcuts.</p>
                </div>
            </div>
            <div class="app-toolbar">
                <a href="{{ route('todos.create') }}" class="btn btn-primary">New Task</a>
                <a href="{{ route('todos.index', ['view' => 'pending']) }}" class="btn btn-soft">Pending</a>
                <a href="{{ route('calendar.index') }}" class="btn btn-soft">Calendar</a>
            </div>
        </div>

        <div class="app-card section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">Recent Notifications</h3>
                    <p class="section-caption">Due soon, overdue, and daily summary events.</p>
                </div>
                <a href="{{ route('notifications.index') }}" class="btn btn-soft">Open</a>
            </div>
            @if($notifications->count())
                <div class="summary-list">
                    @foreach($notifications as $notification)
                        <a href="{{ route('notifications.index') }}" class="summary-item">
                            <div>
                                <h4 class="summary-title">{{ $notification->title }}</h4>
                                <div class="summary-meta">{{ \Illuminate\Support\Str::limit($notification->body, 70) }}</div>
                            </div>
                            <div class="chevron">›</div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="empty-copy mb-0">No reminder activity yet.</p>
            @endif
        </div>

        <div class="app-card section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">Upcoming Tasks</h3>
                    <p class="section-caption">See what is next without clutter.</p>
                </div>
            </div>
            @if($upcomingTasks->count())
                <div class="summary-list">
                    @foreach($upcomingTasks as $task)
                        <a href="{{ route('todos.show', $task) }}" class="summary-item">
                            <div>
                                <h4 class="summary-title">{{ $task->title }}</h4>
                                <div class="summary-meta">{{ $task->due_at ? $task->due_at->format('d M, h:i A') : 'No due time' }}</div>
                            </div>
                            <div class="chevron">›</div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="empty-copy mb-0">No upcoming tasks available.</p>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->hasPermissionTo('admin.dashboard.view'))
<div class="app-card section-card section-space">
    <div class="section-header">
        <div>
            <h3 class="section-title">Admin Access</h3>
            <p class="section-caption">Open management tools for users, roles, analytics, and settings.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light-custom"><i class="bi bi-speedometer2 me-1"></i>Open Admin</a>
    </div>
</div>
@endif

@endsection
