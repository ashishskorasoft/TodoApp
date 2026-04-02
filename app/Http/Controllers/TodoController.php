<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TodoController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly TodoService $todoService)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->hasPermissionTo('tasks.view_own') || $request->user()->hasPermissionTo('tasks.view_all'), 403);

        $baseQuery = $request->user()->todos()
            ->withCount([
                'checklistItems as subtasks_total',
                'checklistItems as subtasks_done' => fn ($query) => $query->where('is_completed', true),
            ]);

        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $baseQuery->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        match ($request->string('view')->toString()) {
            'pending' => $baseQuery->active()->pending(),
            'today' => $baseQuery->active()->dueToday(),
            'upcoming' => $baseQuery->active()->upcoming(),
            'completed' => $baseQuery->completed(),
            'overdue' => $baseQuery->active()->overdue(),
            'archived' => $baseQuery->archived(),
            default => $baseQuery->active(),
        };

        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'], true)) {
            $baseQuery->where('priority', $request->priority);
        }

        if ($request->filled('tag')) {
            $tag = trim((string) $request->string('tag'));
            $baseQuery->whereJsonContains('labels', $tag);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'due_at' => $baseQuery->orderByRaw('due_at is null')->orderBy('due_at'),
            'oldest' => $baseQuery->oldest(),
            default => $baseQuery->latest(),
        };

        $tasks = $baseQuery->paginate(12)->withQueryString();
        $userTodos = $request->user()->todos();

        $stats = [
            'total' => (clone $userTodos)->active()->count(),
            'today' => (clone $userTodos)->active()->dueToday()->count(),
            'completed' => (clone $userTodos)->active()->completed()->count(),
            'overdue' => (clone $userTodos)->active()->overdue()->count(),
        ];

        $tags = (clone $userTodos)->whereNotNull('labels')->pluck('labels')->flatten()->filter()->unique()->values();

        return view('todos.index', compact('tasks', 'stats', 'tags'));
    }

    public function calendar(Request $request): View
    {
        abort_unless($request->user()->hasPermissionTo('calendar.view_own') || $request->user()->hasPermissionTo('calendar.view_all'), 403);

        $anchor = $request->filled('month') ? Carbon::parse($request->string('month').'-01') : now()->startOfMonth();
        $monthStart = $anchor->copy()->startOfMonth()->startOfWeek();
        $monthEnd = $anchor->copy()->endOfMonth()->endOfWeek();

        $tasksByDate = $request->user()->todos()
            ->active()
            ->whereBetween('due_at', [$monthStart, $monthEnd])
            ->orderBy('due_at')
            ->get()
            ->groupBy(fn ($task) => optional($task->due_at)->toDateString());

        $days = [];
        $cursor = $monthStart->copy();
        while ($cursor <= $monthEnd) {
            $days[] = [
                'date' => $cursor->copy(),
                'in_month' => $cursor->month === $anchor->month,
                'tasks' => $tasksByDate->get($cursor->toDateString(), collect()),
            ];
            $cursor->addDay();
        }

        return view('todos.calendar', compact('days', 'anchor'));
    }

    public function create(): View
    {
        return view('todos.create');
    }

    public function store(StoreTodoRequest $request): RedirectResponse
    {
        $todo = $this->todoService->createForUser(
            $request->user(),
            $this->todoService->preparePayload($request),
            $request->input('subtasks', [])
        );

        return redirect()->route('todos.show', $todo)->with('success', 'Task created successfully.');
    }

    public function show(Todo $todo): View
    {
        $this->authorize('view', $todo);
        $todo->load(['checklistItems', 'notifications']);

        return view('todos.show', compact('todo'));
    }

    public function edit(Todo $todo): View
    {
        $this->authorize('update', $todo);
        $todo->load('checklistItems');

        return view('todos.edit', compact('todo'));
    }

    public function update(UpdateTodoRequest $request, Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);
        $this->todoService->updateTodo(
            $todo,
            $this->todoService->preparePayload($request),
            $request->input('subtasks', [])
        );

        return redirect()->route('todos.show', $todo)->with('success', 'Task updated successfully.');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $this->authorize('delete', $todo);
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Task deleted successfully.');
    }

    public function quickToggle(Todo $todo): JsonResponse
    {
        $this->authorize('update', $todo);
        $todo = $this->todoService->toggleCompletion($todo);

        return response()->json([
            'ok' => true,
            'message' => $todo->status === 'completed' ? 'Task finished successfully.' : 'Task moved back to pending.',
            'status' => $todo->status,
        ]);
    }

    public function archive(Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);
        $this->todoService->archive($todo);

        return back()->with('success', 'Task archived successfully.');
    }

    public function restore(Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);
        $this->todoService->restore($todo);

        return back()->with('success', 'Task restored successfully.');
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $ids = collect($request->input('selected', []))->filter()->values();
        if ($ids->isEmpty()) {
            return back()->with('success', 'No tasks selected.');
        }

        $affected = $this->todoService->bulkUpdate(
            $request->user()->todos()->whereIn('id', $ids),
            $request->string('bulk_action')->toString()
        );

        return back()->with('success', $affected > 0 ? 'Bulk action applied successfully.' : 'No changes were applied.');
    }
}
