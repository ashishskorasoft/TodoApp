<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $totalTodos = $user->todos()->count();
        $pendingTodos = $user->todos()->where('status', 'pending')->count();
        $completedTodos = $user->todos()->where('status', 'completed')->count();
        $upcomingTodos = $user->todos()
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', now()->toDateString())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $recentTodos = $user->todos()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalTodos',
            'pendingTodos',
            'completedTodos',
            'upcomingTodos',
            'recentTodos'
        ));
    }
}