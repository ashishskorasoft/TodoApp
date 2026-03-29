<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TodoController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = $request->user()->todos();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'])) {
            $query->where('status', $request->status);
        }

        if ($request->sort === 'due_date') {
            $query->orderBy('due_date', 'asc');
        } elseif ($request->sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $todos = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => $request->user()->todos()->count(),
            'pending' => $request->user()->todos()->where('status', 'pending')->count(),
            'completed' => $request->user()->todos()->where('status', 'completed')->count(),
        ];

        return view('todos.index', compact('todos', 'stats'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(StoreTodoRequest $request)
    {
        $request->user()->todos()->create($request->validated());

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo created successfully.');
    }

    public function show(Todo $todo)
    {
        $this->authorize('view', $todo);

        return view('todos.show', compact('todo'));
    }

    public function edit(Todo $todo)
    {
        $this->authorize('update', $todo);

        return view('todos.edit', compact('todo'));
    }

    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $todo->update($request->validated());

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo updated successfully.');
    }

    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo deleted successfully.');
    }
}