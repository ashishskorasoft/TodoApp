<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $userTodos = $user->todos()->active();

        $total = (clone $userTodos)->count();
        $pending = (clone $userTodos)->pending()->count();
        $completed = (clone $userTodos)->completed()->count();
        $overdue = (clone $userTodos)->overdue()->count();

        $todayTasks = (clone $userTodos)->dueToday()->orderBy('due_at')->take(6)->get();
        $upcomingTasks = (clone $userTodos)->upcoming()->orderBy('due_at')->take(4)->get();
        $notifications = $user->todoNotifications()->take(5)->get();

        $completionRate = $total > 0 ? (int) round(($completed / max($total, 1)) * 100) : 0;
        $focusMessage = match (true) {
            $overdue > 0 => 'You have overdue work. Clear the delayed items first for a calmer day.',
            $todayTasks->count() > 0 => 'You have active work lined up for today. Stay focused and close the top items first.',
            $upcomingTasks->count() > 0 => 'Today looks light. This is a good window to prepare for upcoming tasks.',
            default => 'Your workspace is clear right now. Add a fresh task and keep the momentum going.',
        };

        return view('dashboard', compact(
            'total',
            'pending',
            'completed',
            'overdue',
            'todayTasks',
            'upcomingTasks',
            'notifications',
            'completionRate',
            'focusMessage'
        ));
    }
}
