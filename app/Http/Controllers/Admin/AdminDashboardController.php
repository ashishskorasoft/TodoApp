<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesPermissions;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Todo;
use App\Models\TodoNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    use AuthorizesPermissions;

    public function index(Request $request): View
    {
        $this->requirePermission($request, 'admin.dashboard.view');

        $totals = [
            'users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'tasks' => Todo::count(),
            'pending' => Todo::where('status', 'pending')->where('is_archived', false)->count(),
            'completed' => Todo::where('status', 'completed')->count(),
            'overdue' => Todo::where('status', '!=', 'completed')->whereNotNull('due_at')->where('due_at', '<', now())->where('is_archived', false)->count(),
            'notifications' => TodoNotification::count(),
        ];

        $recentUsers = User::with('roles')->latest()->take(6)->get();
        $recentTasks = Todo::with('user')->latest()->take(6)->get();
        $roleBreakdown = Role::query()
            ->withCount('users')
            ->orderBy('sort_order')
            ->get()
            ->pluck('users_count', 'code');

        return view('admin.dashboard.index', compact('totals', 'recentUsers', 'recentTasks', 'roleBreakdown'));
    }
}
