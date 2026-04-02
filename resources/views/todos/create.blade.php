@extends('layouts.app', ['title' => 'Create Task | TodoFlow'])

@section('page_title', 'Add Task')
@section('page_subtitle', 'Quick create flow designed for mobile-first task capture.')

@section('content')
<div class="app-card section-card section-space">
    <div class="section-header">
        <div>
            <h3 class="section-title">New Task</h3>
            <p class="section-caption">Use the same Phase 1 flow with added recurrence, reminders, tags, and subtasks.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('todos.store') }}" data-loader="true">
        @csrf
        @include('todos._form')
        <div class="app-toolbar mt-3">
            <button class="btn btn-primary">Save Task</button>
            <a href="{{ route('todos.index') }}" class="btn btn-soft">Cancel</a>
        </div>
    </form>
</div>
@endsection
