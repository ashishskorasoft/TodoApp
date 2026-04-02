<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesPermissions;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    use AuthorizesPermissions;

    public function index(Request $request): View
    {
        $this->requirePermission($request, 'users.view');

        $users = User::query()
            ->with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->string('search'));
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $roles = Role::query()->orderBy('sort_order')->pluck('name', 'code')->all();
        if (empty($roles)) {
            $roles = User::roleOptions();
        }

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function edit(Request $request, User $user): View
    {
        $this->requirePermission($request, 'users.update');

        return view('admin.users.edit', [
            'managedUser' => $user->load('roles'),
            'roles' => Role::query()->orderBy('sort_order')->get(['name', 'code']),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->requirePermission($request, 'users.update');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::exists('roles', 'code')],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);
        $user->assignRoleByCode($validated['role']);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}
