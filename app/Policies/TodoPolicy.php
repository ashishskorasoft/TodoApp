<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;

class TodoPolicy
{
    public function view(User $user, Todo $todo): bool
    {
        return $user->hasPermissionTo('tasks.view_all') || ($user->hasPermissionTo('tasks.view_own') && $user->id === $todo->user_id);
    }

    public function update(User $user, Todo $todo): bool
    {
        return $user->hasPermissionTo('tasks.update_all') || ($user->hasPermissionTo('tasks.update_own') && $user->id === $todo->user_id);
    }

    public function delete(User $user, Todo $todo): bool
    {
        return $user->hasPermissionTo('tasks.delete_all') || ($user->hasPermissionTo('tasks.delete_own') && $user->id === $todo->user_id);
    }
}
