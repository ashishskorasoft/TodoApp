@extends('layouts.app')

@section('page_title', 'Task Details')
@section('page_subtitle', 'Review complete task information and next actions.')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="app-card section-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <div class="section-title">{{ $todo->title }}</div>
                    <div class="section-subtitle">Created on {{ $todo->created_at->format('d M Y') }}</div>
                </div>
                <span class="badge-status {{ $todo->status === 'completed' ? 'badge-completed' : 'badge-pending' }}">
                    {{ ucfirst($todo->status) }}
                </span>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold">Description</h6>
                <p class="text-muted mb-0">{{ $todo->description ?: 'No description provided.' }}</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="app-card p-3">
                        <small class="text-muted d-block mb-1">Due Date</small>
                        <strong>{{ $todo->due_date ? $todo->due_date->format('d M Y') : 'N/A' }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="app-card p-3">
                        <small class="text-muted d-block mb-1">Current Status</small>
                        <strong>{{ ucfirst($todo->status) }}</strong>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('todos.edit', $todo) }}" class="btn btn-accent">Edit Task</a>
                <a href="{{ route('todos.index') }}" class="btn btn-light-custom">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection