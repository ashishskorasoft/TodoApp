<?php

namespace App\Support;

use App\Models\User;

class PermissionCatalog
{
    public static function grouped(): array
    {
        return [
            'dashboard' => [
                'dashboard.view',
                'admin.dashboard.view',
            ],
            'tasks' => [
                'tasks.view_own',
                'tasks.view_all',
                'tasks.create',
                'tasks.update_own',
                'tasks.update_all',
                'tasks.delete_own',
                'tasks.delete_all',
                'tasks.archive_own',
                'tasks.archive_all',
                'tasks.complete_own',
                'tasks.complete_all',
            ],
            'calendar' => [
                'calendar.view_own',
                'calendar.view_all',
            ],
            'notifications' => [
                'notifications.view_own',
                'notifications.manage',
            ],
            'profile' => [
                'profile.manage_own',
            ],
            'reminders' => [
                'reminders.manage_own',
                'reminders.manage_all',
            ],
            'users' => [
                'users.view',
                'users.update',
                'users.activate',
            ],
            'roles' => [
                'roles.view',
                'roles.manage',
            ],
            'settings' => [
                'settings.view',
                'settings.update',
            ],
        ];
    }

    public static function all(): array
    {
        return collect(static::grouped())->flatten()->values()->all();
    }

    public static function matrix(): array
    {
        return [
            User::ROLE_SUPER_ADMIN => static::all(),
            User::ROLE_ADMIN => [
                'dashboard.view',
                'admin.dashboard.view',
                'tasks.view_own',
                'tasks.view_all',
                'tasks.create',
                'tasks.update_own',
                'tasks.update_all',
                'tasks.delete_own',
                'tasks.delete_all',
                'tasks.archive_own',
                'tasks.archive_all',
                'tasks.complete_own',
                'tasks.complete_all',
                'calendar.view_own',
                'calendar.view_all',
                'notifications.view_own',
                'notifications.manage',
                'profile.manage_own',
                'reminders.manage_own',
                'reminders.manage_all',
                'users.view',
                'users.update',
                'users.activate',
                'roles.view',
                'roles.manage',
                'settings.view',
                'settings.update',
            ],
            User::ROLE_MANAGER => [
                'dashboard.view',
                'admin.dashboard.view',
                'tasks.view_own',
                'tasks.view_all',
                'tasks.create',
                'tasks.update_own',
                'tasks.update_all',
                'tasks.archive_own',
                'tasks.archive_all',
                'tasks.complete_own',
                'tasks.complete_all',
                'calendar.view_own',
                'calendar.view_all',
                'notifications.view_own',
                'profile.manage_own',
                'reminders.manage_own',
                'users.view',
                'roles.view',
                'settings.view',
            ],
            User::ROLE_USER => [
                'dashboard.view',
                'tasks.view_own',
                'tasks.create',
                'tasks.update_own',
                'tasks.delete_own',
                'tasks.archive_own',
                'tasks.complete_own',
                'calendar.view_own',
                'notifications.view_own',
                'profile.manage_own',
                'reminders.manage_own',
            ],
        ];
    }

    public static function labels(): array
    {
        return [
            'dashboard.view' => 'Open personal dashboard',
            'admin.dashboard.view' => 'Open admin dashboard',
            'tasks.view_own' => 'View own tasks',
            'tasks.view_all' => 'View all user tasks',
            'tasks.create' => 'Create tasks',
            'tasks.update_own' => 'Edit own tasks',
            'tasks.update_all' => 'Edit any task',
            'tasks.delete_own' => 'Delete own tasks',
            'tasks.delete_all' => 'Delete any task',
            'tasks.archive_own' => 'Archive own tasks',
            'tasks.archive_all' => 'Archive any task',
            'tasks.complete_own' => 'Complete own tasks',
            'tasks.complete_all' => 'Complete any task',
            'calendar.view_own' => 'View own calendar',
            'calendar.view_all' => 'View team calendar data',
            'notifications.view_own' => 'View own notifications',
            'notifications.manage' => 'Manage notification operations',
            'profile.manage_own' => 'Manage own profile',
            'reminders.manage_own' => 'Manage own reminders',
            'reminders.manage_all' => 'Manage reminder system',
            'users.view' => 'View users',
            'users.update' => 'Update users',
            'users.activate' => 'Activate or deactivate users',
            'roles.view' => 'View role matrix',
            'roles.manage' => 'Manage roles and permissions',
            'settings.view' => 'View settings',
            'settings.update' => 'Update settings',
        ];
    }
}
