@extends('layouts.app', ['title' => 'Create Role | TodoFlow'])

@section('page_title', 'Create Role')
@section('page_subtitle', 'Build a custom role without disturbing your app design or core workflow.')

@section('content')
<form method="POST" action="{{ route('admin.roles.store') }}" class="form-stack">
    @csrf
    @include('admin.roles._form', ['submitLabel' => 'Create Role'])
</form>
@endsection
