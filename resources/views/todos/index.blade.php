@extends('layouts.app')

@section('page_title', 'My Tasks')
@section('page_subtitle', 'Track, filter and manage all your work in one place.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="app-card stat-card">
            <div class="stat-label">Total Tasks</div>
            <h3 class="stat-value">{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="app-card stat-card">
            <div class="stat-label">Pending</div>
            <h3 class="stat-value">{{ $stats['pending'] }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="app-card stat-card">
            <div class="stat-label">Completed</div>
            <h3 class="stat-value">{{ $stats['completed'] }}</h3>
        </div>
    </div>
</div>

<div class="app-card section-card mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div>
            <div class="section-title">Task Filters</div>
            <div class="section-subtitle">Search and organize your tasks quickly.</div>
        </div>
        <a href="{{ route('todos.create') }}" class="btn btn-primary">+ New Task</a>
    </div>

    <form method="GET" action="{{ route('todos.index') }}">
        <div class="row g-3">
            <div class="col-lg-4">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by title..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="col-lg-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-lg-3">
                <select name="sort" class="form-select">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="due_date" {{ request('sort') === 'due_date' ? 'selected' : '' }}>Due Date</option>
                </select>
            </div>

            <div class="col-lg-2 d-grid">
                <button class="btn btn-accent">Apply</button>
            </div>
        </div>
    </form>
</div>

<div class="app-card table-card">
    @if($todos->count())
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($todos as $todo)
                    <tr>
                        <td>
                            <div class="todo-title">{{ $todo->title }}</div>
                            <div class="todo-meta">
                                {{ $todo->description ? \Illuminate\Support\Str::limit($todo->description, 70) : 'No description added' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge-status {{ $todo->status === 'completed' ? 'badge-completed' : 'badge-pending' }}">
                                {{ ucfirst($todo->status) }}
                            </span>
                        </td>
                        <td>{{ $todo->due_date ? $todo->due_date->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $todo->created_at->format('d M Y') }}</td>
  
</td><td class="text-end">
    <div class="d-flex justify-content-end flex-wrap gap-2">
        <a href="{{ route('todos.show', $todo) }}" class="btn btn-light-custom btn-sm">View</a>
        <a href="{{ route('todos.edit', $todo) }}" class="btn btn-accent btn-sm">Edit</a>
        <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Delete this task?')" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    </div>
</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $todos->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">+</div>
            <h4>No tasks found</h4>
            <p class="text-muted">Try changing filters or create a new task.</p>
            <a href="{{ route('todos.create') }}" class="btn btn-primary">Create Task</a>
        </div>
    @endif
</div>
@endsection