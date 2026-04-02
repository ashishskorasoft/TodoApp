@extends('layouts.app', ['title' => 'Edit User | TodoFlow'])

@section('page_title', 'Edit User')
@section('page_subtitle', 'Update account identity, role, and access state.')

@section('content')
<div class="app-card section-card section-space">
    <form method="POST" action="{{ route('admin.users.update', $managedUser) }}" class="form-stack">
        @csrf
        @method('PATCH')

        <div class="input-shell">
            <label class="field-label">Name</label>
            <input type="text" name="name" value="{{ old('name', $managedUser->name) }}" class="form-control" required>
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $managedUser->email) }}" class="form-control" required>
            @error('email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="input-shell">
            <label class="field-label">Role</label>
            <select name="role" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role->code }}" @selected(old('role', $managedUser->primaryRoleCode()) === $role->code)>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <label class="switch-row">
            <span>Account Active</span>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $managedUser->is_active))>
        </label>

        <div class="task-form-actions">
            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Save User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light-custom">Back</a>
        </div>
    </form>
</div>
@endsection
