@extends('layouts.app', ['title' => 'Roles | TodoFlow'])

@section('page_title', 'Roles & Permissions')
@section('page_subtitle', 'Database-backed access control with editable role definitions and permission mapping.')

@section('content')
<div class="app-card section-card section-space">
    <div class="section-header">
        <div>
            <h3 class="section-title">Role controls</h3>
            <p class="section-caption">Manage company-style roles without changing the app design or workflow.</p>
        </div>
        @if(auth()->user()->hasPermissionTo('roles.manage'))
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary"><i class="bi bi-shield-plus me-1"></i>New Role</a>
        @endif
    </div>
</div>

<div class="task-stack section-space">
    @foreach($roleMatrix as $role)
        <div class="app-card section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">{{ $role->name }}</h3>
                    <p class="section-caption">{{ $role->code }} · {{ $role->description ?: 'No description added yet.' }}</p>
                </div>
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <span class="chip chip-low">{{ $role->permissions->count() }} permissions</span>
                    <span class="chip {{ $role->is_system ? 'chip-overdue' : 'chip-pending' }}">{{ $role->is_system ? 'System role' : 'Custom role' }}</span>
                    <span class="chip chip-completed">{{ $role->users->count() }} users</span>
                    @if(auth()->user()->hasPermissionTo('roles.manage'))
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-soft">Edit</a>
                        @if(!$role->is_system)
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this custom role?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light-custom" type="submit">Delete</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            <div class="permission-list">
                @forelse($role->permissions->sortBy(['group', 'name']) as $permission)
                    <div class="activity-row align-start">
                        <div>
                            <div class="task-row-title">{{ $permissionLabels[$permission->code] ?? $permission->name }}</div>
                            <div class="task-row-meta">{{ $permission->code }}</div>
                        </div>
                        <span class="chip chip-low">{{ ucfirst($permission->group) }}</span>
                    </div>
                @empty
                    <div class="empty-lite">No permissions assigned yet.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
