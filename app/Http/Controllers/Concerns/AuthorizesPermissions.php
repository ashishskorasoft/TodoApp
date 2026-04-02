<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait AuthorizesPermissions
{
    protected function requirePermission(Request $request, string $permission): void
    {
        abort_unless($request->user()?->hasPermissionTo($permission), 403);
    }

    protected function requireAnyPermission(Request $request, array $permissions): void
    {
        abort_unless($request->user()?->hasAnyPermission($permissions), 403);
    }
}
