<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Support\PermissionCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleManagementController extends Controller
{
    use AuthorizesPermissions;

    public function index(Request $request): View
    {
        $this->requirePermission($request, 'roles.view');

        $permissionLabels = PermissionCatalog::labels();
        $permissionGroups = Permission::query()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group');

        $roleMatrix = Role::query()
            ->with(['permissions', 'users'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.roles.index', compact('roleMatrix', 'permissionGroups', 'permissionLabels'));
    }

    public function create(Request $request): View
    {
        $this->requirePermission($request, 'roles.manage');

        $role = new Role([
            'sort_order' => (int) Role::max('sort_order') + 1,
        ]);

        return view('admin.roles.create', [
            'role' => $role,
            'permissionGroups' => Permission::query()->orderBy('group')->orderBy('name')->get()->groupBy('group'),
            'selectedPermissions' => [],
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->requirePermission($request, 'roles.manage');

        $validated = $request->validated();
        $role = Role::query()->create([
            'name' => $validated['name'],
            'code' => Str::slug($validated['code'], '_'),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ((int) Role::max('sort_order') + 1),
            'is_system' => false,
        ]);

        $permissionIds = Permission::query()->whereIn('code', $validated['permissions'] ?? [])->pluck('id')->all();
        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Request $request, Role $role): View
    {
        $this->requirePermission($request, 'roles.manage');

        $role->load('permissions');

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => Permission::query()->orderBy('group')->orderBy('name')->get()->groupBy('group'),
            'selectedPermissions' => $role->permissions->pluck('code')->all(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->requirePermission($request, 'roles.manage');

        $validated = $request->validated();
        $role->update([
            'name' => $validated['name'],
            'code' => $role->is_system ? $role->code : Str::slug($validated['code'], '_'),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $role->sort_order,
        ]);

        $permissionIds = Permission::query()->whereIn('code', $validated['permissions'] ?? [])->pluck('id')->all();
        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        $this->requirePermission($request, 'roles.manage');

        abort_if($role->is_system, 422, 'System roles cannot be deleted.');
        abort_if($role->users()->exists(), 422, 'Remove users from this role before deleting it.');

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
