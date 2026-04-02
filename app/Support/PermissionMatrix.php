<?php

namespace App\Support;

class PermissionMatrix
{
    public static function labels(): array
    {
        return [
            'admin.dashboard.view' => 'View admin dashboard',
            'users.view' => 'View users',
            'users.update' => 'Update users',
            'roles.view' => 'View roles',
            'settings.view' => 'View settings',
            'settings.update' => 'Update settings',
            'todos.own.view' => 'View own todos',
            'todos.own.manage' => 'Manage own todos',
            'todos.all.view' => 'View all todos',
            'notifications.view' => 'View notifications',
        ];
    }
}
