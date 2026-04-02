@extends('layouts.app', ['title' => 'Edit Role | TodoFlow'])

@section('page_title', 'Edit Role')
@section('page_subtitle', 'Adjust role labels, ordering, and permissions for a safer production setup.')

@section('content')
<form method="POST" action="{{ route('admin.roles.update', $role) }}" class="form-stack">
    @csrf
    @method('PATCH')
    @include('admin.roles._form', ['submitLabel' => 'Save Role'])
</form>
@endsection
