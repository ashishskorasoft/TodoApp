@extends('layouts.app', ['title' => 'Tasks | TodoFlow'])

@section('page_title', 'Tasks')
@section('page_subtitle', 'Recurring, tagged, and reminder-ready tasks in one fast mobile flow.')

@section('content')
<div class="filter-tabs section-space">
    @php $currentView = request('view', 'all'); @endphp
    @foreach(['all' => 'All', 'today' => 'Today', 'upcoming' => 'Upcoming', 'overdue' => 'Overdue'] as $key => $label)
        <a href="{{ route('todos.index', array_merge(request()->except('page'), $key === 'all' ? ['view' => null] : ['view' => $key])) }}" class="filter-tab {{ $currentView === $key ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>
<div class="filter-tabs section-space" style="grid-template-columns: repeat(2,1fr);">
    @foreach(['completed' => 'Completed', 'archived' => 'Archived'] as $key => $label)
        <a href="{{ route('todos.index', array_merge(request()->except('page'), ['view' => $key])) }}" class="filter-tab {{ $currentView === $key ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

<div class="app-card section-card section-space">
    <form method="GET">
        <div class="app-form">
            <div class="form-group">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search task title or notes">
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="">All priorities</option>
                        <option value="low" @selected(request('priority')==='low')>Low</option>
                        <option value="medium" @selected(request('priority')==='medium')>Medium</option>
                        <option value="high" @selected(request('priority')==='high')>High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Sort</label>
                    <select name="sort" class="form-select">
                        <option value="latest" @selected(request('sort')==='latest')>Latest</option>
                        <option value="due_at" @selected(request('sort')==='due_at')>Due date</option>
                        <option value="oldest" @selected(request('sort')==='oldest')>Oldest</option>
                    </select>
                </div>
            </div>
            @if($tags->count())
                <div class="task-chips mt-2">
                    @foreach($tags as $tag)
                        <a href="{{ route('todos.index', array_merge(request()->except('page'), ['tag' => $tag])) }}" class="chip chip-tag">{{ $tag }}</a>
                    @endforeach
                </div>
            @endif
            <div class="app-toolbar mt-3">
                <button class="btn btn-primary">Apply</button>
                <a href="{{ route('todos.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </div>
    </form>
</div>

<form action="{{ route('todos.bulk-update') }}" method="POST" data-loader="true">
    @csrf
    <div class="app-card section-card section-space">
        <div class="section-header">
            <div>
                <h3 class="section-title">Task List</h3>
                <p class="section-caption">Fast mobile task cards with bulk actions and quick status handling.</p>
            </div>
        </div>
        <div class="inline-fields mb-3">
            <div class="form-group mb-0">
                <label class="form-label">Bulk Action</label>
                <select name="bulk_action" class="form-select">
                    <option value="complete">Mark completed</option>
                    <option value="archive">Archive</option>
                    <option value="restore">Restore</option>
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div class="form-group mb-0 d-flex align-items-end">
                <button class="btn btn-soft w-100">Apply Selected</button>
            </div>
        </div>

        <div class="task-stack">
            @forelse($tasks as $task)
                <div class="app-card" style="overflow:hidden;">
                    <div class="task-card">
                        <label class="select-box"><input type="checkbox" name="selected[]" value="{{ $task->id }}"></label>
                        <a href="{{ route('todos.show', $task) }}" class="task-card-link" style="display:block; min-width:0;">
                            <div class="task-content">
                                <div class="task-title-row"><h4 class="task-title">{{ $task->title }}</h4></div>
                                <p class="task-time">{{ $task->due_at ? $task->due_at->format('d M, h:i A') : 'No due date' }}</p>
                                <div class="task-chips">
                                    <span class="chip chip-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                    <span class="chip {{ $task->is_overdue ? 'chip-overdue' : 'chip-'.$task->status }}">{{ $task->is_overdue ? 'Overdue' : ucfirst($task->status) }}</span>
                                    @if($task->has_recurrence)<span class="chip chip-tag">{{ ucfirst($task->repeat_type) }}</span>@endif
                                    @if($task->subtasks_total)<span class="chip chip-tag">{{ $task->subtasks_done }}/{{ $task->subtasks_total }} subtasks</span>@endif
                                    @foreach(array_slice((array) $task->labels, 0, 2) as $label)<span class="chip chip-tag">{{ $label }}</span>@endforeach
                                </div>
                            </div>
                        </a>
                        <div class="d-flex flex-column gap-2 align-items-end">
                            <button type="button" class="btn btn-soft" data-task-toggle data-url="{{ route('todos.quick-toggle', $task) }}" style="min-height:auto; padding:8px 10px;">Toggle</button>
                            <span class="chevron">›</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">+</div>
                    <h4 class="empty-title">No tasks found</h4>
                    <p class="empty-copy">Try another view or create a new task.</p>
                </div>
            @endforelse
        </div>
        <div class="pt-3">{{ $tasks->links() }}</div>
    </div>
</form>
@endsection
