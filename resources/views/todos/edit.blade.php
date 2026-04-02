@extends('layouts.app', ['title' => 'Edit Task | TodoFlow'])

@section('page_title', 'Edit Task')
@section('page_subtitle', 'Update your task using the same compact Phase 1 flow.')

@section('content')
<div class="app-card section-card section-space">
    <div class="section-header">
        <div>
            <h3 class="section-title">Update Task</h3>
            <p class="section-caption">Edit timing, recurrence, subtasks, and tags without changing the design pattern.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('todos.update', $todo) }}" data-loader="true">
        @csrf
        @method('PUT')
        @include('todos._form')
        <div class="app-toolbar mt-3">
            <button class="btn btn-primary">Update Task</button>
            <a href="{{ route('todos.show', $todo) }}" class="btn btn-soft">Back</a>
        </div>
    </form>
</div>
@endsection
