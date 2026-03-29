@extends('layouts.app')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'A clear overview of your productivity and upcoming work.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="app-card stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Tasks</div>
                    <h3 class="stat-value">{{ $totalTodos }}</h3>
                </div>
                <div class="stat-icon bg-soft-primary">T</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="app-card stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Pending Tasks</div>
                    <h3 class="stat-value">{{ $pendingTodos }}</h3>
                </div>
                <div class="stat-icon bg-soft-accent">P</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="app-card stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Completed Tasks</div>
                    <h3 class="stat-value">{{ $completedTodos }}</h3>
                </div>
                <div class="stat-icon bg-soft-success">C</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 g-md-4 mb-3 mb-md-4">
    <div class="col-lg-6">
        <div class="app-card section-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-title">Upcoming Deadlines</div>
                    <div class="section-subtitle">Your nearest due tasks.</div>
                </div>
                <a href="{{ route('todos.index', ['sort' => 'due_date']) }}" class="btn btn-light-custom btn-sm">View All</a>
            </div>

            @if($upcomingTodos->count())
                <div class="list-group list-group-flush">
                    @foreach($upcomingTodos as $todo)
                        <div class="list-group-item px-0 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="todo-title">{{ $todo->title }}</div>
                                    <div class="todo-meta">
                                        Due: {{ $todo->due_date ? $todo->due_date->format('d M Y') : 'N/A' }}
                                    </div>
                                </div>
                                <span class="badge-status {{ $todo->status === 'completed' ? 'badge-completed' : 'badge-pending' }}">
                                    {{ ucfirst($todo->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">✓</div>
                    <h5>No upcoming deadlines</h5>
                    <p class="text-muted mb-0">You're all caught up for now.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="app-card section-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-title">Recent Tasks</div>
                    <div class="section-subtitle">Latest created tasks in your account.</div>
                </div>
                <a href="{{ route('todos.create') }}" class="btn btn-primary btn-sm">Add Task</a>
            </div>

            @if($recentTodos->count())
                <div class="list-group list-group-flush">
                    @foreach($recentTodos as $todo)
                        <div class="list-group-item px-0 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="todo-title">{{ $todo->title }}</div>
                                    <div class="todo-meta">
                                        {{ $todo->description ? \Illuminate\Support\Str::limit($todo->description, 60) : 'No description added' }}
                                    </div>
                                </div>
                                <a href="{{ route('todos.show', $todo) }}" class="btn btn-light-custom btn-sm">Open</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">+</div>
                    <h5>No tasks yet</h5>
                    <p class="text-muted mb-3">Start by creating your first task.</p>
                    <a href="{{ route('todos.create') }}" class="btn btn-primary">Create Task</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection