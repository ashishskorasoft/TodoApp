@extends('layouts.app', ['title' => 'Users | TodoFlow'])

@section('page_title', 'User Management')
@section('page_subtitle', 'Search, review, and manage user accounts and access.')

@section('content')
<div class="app-card section-card section-space">
    <form method="GET" action="{{ route('admin.users.index') }}" class="inline-search">
        <div class="input-shell icon-input">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control input-min" placeholder="Search users by name or email">
        </div>
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
</div>

<div class="app-card section-card section-space">
    <div class="task-stack">
        @foreach($users as $user)
            <a href="{{ route('admin.users.edit', $user) }}" class="task-card-link app-card">
                <div class="task-card">
                    <div class="task-toggle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="task-content">
                        <div class="task-title-row"><h4 class="task-title">{{ $user->name }}</h4></div>
                        <p class="task-time">{{ $user->email }}</p>
                        <div class="task-chips">
                            <span class="chip chip-{{ $user->is_active ? 'completed' : 'pending' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            <span class="chip chip-low">{{ $user->primaryRoleLabel() }}</span>
                        </div>
                    </div>
                    <div class="task-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="pt-3">{{ $users->links() }}</div>
</div>
@endsection
