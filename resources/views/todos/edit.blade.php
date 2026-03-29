@extends('layouts.app')

@section('page_title', 'Edit Task')
@section('page_subtitle', 'Update task information and keep everything current.')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="app-card section-card">
            <div class="section-title">Edit Task</div>
            <div class="section-subtitle">Modify the selected task details below.</div>

            <form action="{{ route('todos.update', $todo) }}" method="POST">
                @csrf
                @method('PUT')
                @include('todos._form')

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="{{ route('todos.index') }}" class="btn btn-light-custom">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection