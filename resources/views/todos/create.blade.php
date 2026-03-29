@extends('layouts.app')

@section('page_title', 'Create Task')
@section('page_subtitle', 'Add a new task with clear details and timeline.')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="app-card section-card">
            <div class="section-title">New Task</div>
            <div class="section-subtitle">Fill out the form below to add a new task.</div>

            <form action="{{ route('todos.store') }}" method="POST">
                @csrf
                @include('todos._form')

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save Task</button>
                    <a href="{{ route('todos.index') }}" class="btn btn-light-custom">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection