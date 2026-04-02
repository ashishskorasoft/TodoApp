<?php

use App\Models\Permission;
use App\Models\Role;
use App\Support\PermissionCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'is_archived'], 'todos_user_status_archived_idx');
            $table->index(['user_id', 'due_at'], 'todos_user_due_at_idx');
            $table->index(['user_id', 'priority'], 'todos_user_priority_idx');
        });

        Schema::table('todo_notifications', function (Blueprint $table) {
            $table->index(['user_id', 'read_at'], 'todo_notifications_user_read_idx');
            $table->index(['user_id', 'delivered_at'], 'todo_notifications_user_delivered_idx');
            $table->index(['todo_id', 'type'], 'todo_notifications_todo_type_idx');
        });

        Schema::table('todo_checklist_items', function (Blueprint $table) {
            $table->index(['todo_id', 'position'], 'todo_checklist_todo_position_idx');
        });

        $roles = Role::query()->get()->keyBy('code');
        $permissions = Permission::query()->pluck('id', 'code');
        $now = now();

        foreach (PermissionCatalog::matrix() as $roleCode => $permissionCodes) {
            $role = $roles->get($roleCode);
            if (! $role) {
                continue;
            }

            foreach ($permissionCodes as $permissionCode) {
                $permissionId = $permissions[$permissionCode] ?? null;
                if (! $permissionId) {
                    continue;
                }

                DB::table('permission_role')->updateOrInsert(
                    ['role_id' => $role->id, 'permission_id' => $permissionId],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }
    }

    public function down(): void
    {
        Schema::table('todo_checklist_items', function (Blueprint $table) {
            $table->dropIndex('todo_checklist_todo_position_idx');
        });

        Schema::table('todo_notifications', function (Blueprint $table) {
            $table->dropIndex('todo_notifications_user_read_idx');
            $table->dropIndex('todo_notifications_user_delivered_idx');
            $table->dropIndex('todo_notifications_todo_type_idx');
        });

        Schema::table('todos', function (Blueprint $table) {
            $table->dropIndex('todos_user_status_archived_idx');
            $table->dropIndex('todos_user_due_at_idx');
            $table->dropIndex('todos_user_priority_idx');
        });
    }
};
