@extends('layouts.app', ['title' => 'Admin Dashboard | TodoFlow'])

@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Manage users, roles, settings, and platform health from one mobile-first workspace.')

@section('content')
<div class="metric-grid section-space">
    <div class="app-card metric-card"><div class="metric-top"><span class="metric-label">Users</span><span class="metric-icon">US</span></div><div class="metric-value">{{ $totals['users'] }}</div></div>
    <div class="app-card metric-card"><div class="metric-top"><span class="metric-label">Active</span><span class="metric-icon">AC</span></div><div class="metric-value">{{ $totals['active_users'] }}</div></div>
    <div class="app-card metric-card"><div class="metric-top"><span class="metric-label">Tasks</span><span class="metric-icon">TS</span></div><div class="metric-value">{{ $totals['tasks'] }}</div></div>
    <div class="app-card metric-card"><div class="metric-top"><span class="metric-label">Pending</span><span class="metric-icon">PN</span></div><div class="metric-value">{{ $totals['pending'] }}</div></div>
    <div class="app-card metric-card"><div class="metric-top"><span class="metric-label">Done</span><span class="metric-icon">DN</span></div><div class="metric-value">{{ $totals['completed'] }}</div></div>
    <div class="app-card metric-card"><div class="metric-top"><span class="metric-label">Overdue</span><span class="metric-icon">OD</span></div><div class="metric-value">{{ $totals['overdue'] }}</div></div>
</div>

<div class="desktop-two section-space">
    <div class="app-card section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Admin Shortcuts</h3>
                <p class="section-caption">Quick links for operations and monitoring.</p>
            </div>
        </div>
        <div class="admin-link-grid">
            <a href="{{ route('admin.users.index') }}" class="app-card admin-mini-card"><i class="bi bi-people"></i><span>Users</span></a>
            <a href="{{ route('admin.roles.index') }}" class="app-card admin-mini-card"><i class="bi bi-shield-check"></i><span>Roles</span></a>
            <a href="{{ route('admin.settings.edit') }}" class="app-card admin-mini-card"><i class="bi bi-sliders"></i><span>Settings</span></a>
            <a href="{{ route('notifications.index') }}" class="app-card admin-mini-card"><i class="bi bi-bell"></i><span>Alerts</span></a>
        </div>
    </div>

    <div class="app-card section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Role Breakdown</h3>
                <p class="section-caption">Current user access distribution.</p>
            </div>
        </div>
        <div class="task-stack">
            @foreach(\App\Models\User::roleOptions() as $roleKey => $roleLabel)
                <div class="activity-row">
                    <div>
                        <div class="task-row-title">{{ $roleLabel }}</div>
                        <div class="task-row-meta">{{ $roleKey }}</div>
                    </div>
                    <strong>{{ $roleBreakdown[$roleKey] ?? 0 }}</strong>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="desktop-two section-space">
    <div class="app-card section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Recent Users</h3>
                <p class="section-caption">Newest accounts in the workspace.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="mini-link">View all</a>
        </div>
        <div class="task-stack">
            @foreach($recentUsers as $user)
                <div class="activity-row">
                    <div>
                        <div class="task-row-title">{{ $user->name }}</div>
                        <div class="task-row-meta">{{ $user->email }}</div>
                    </div>
                    <span class="chip chip-{{ $user->is_active ? 'completed' : 'pending' }}">{{ $user->primaryRoleLabel() }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="app-card section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Recent Tasks</h3>
                <p class="section-caption">Latest task activity across all users.</p>
            </div>
        </div>
        <div class="task-stack">
            @foreach($recentTasks as $task)
                <div class="activity-row align-start">
                    <div>
                        <div class="task-row-title">{{ $task->title }}</div>
                        <div class="task-row-meta">{{ optional($task->user)->name ?? 'Unknown user' }} • {{ ucfirst($task->priority) }}</div>
                    </div>
                    <span class="chip {{ $task->status === 'completed' ? 'chip-completed' : 'chip-pending' }}">{{ ucfirst($task->status) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
