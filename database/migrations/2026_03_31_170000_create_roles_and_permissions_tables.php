<?php

use App\Models\User;
use App\Support\PermissionCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('group')->index();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'user_id']);
        });

        $now = now();
        $roleOptions = User::roleOptions();
        $roleDescriptions = [
            User::ROLE_SUPER_ADMIN => 'Full system control with unrestricted administrative access.',
            User::ROLE_ADMIN => 'Operational admin role for users, settings, and productivity oversight.',
            User::ROLE_MANAGER => 'Read-heavy management role for oversight and team visibility.',
            User::ROLE_USER => 'Standard task management role focused on personal productivity.',
        ];

        foreach (array_values(array_keys($roleOptions)) as $index => $code) {
            DB::table('roles')->insert([
                'name' => $roleOptions[$code],
                'code' => $code,
                'description' => $roleDescriptions[$code] ?? null,
                'is_system' => true,
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (PermissionCatalog::grouped() as $group => $codes) {
            foreach ($codes as $code) {
                DB::table('permissions')->insert([
                    'name' => PermissionCatalog::labels()[$code] ?? $code,
                    'code' => $code,
                    'group' => $group,
                    'description' => PermissionCatalog::labels()[$code] ?? $code,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $roles = DB::table('roles')->pluck('id', 'code');
        $permissions = DB::table('permissions')->pluck('id', 'code');

        foreach (PermissionCatalog::matrix() as $roleCode => $permissionCodes) {
            $roleId = $roles[$roleCode] ?? null;
            if (! $roleId) {
                continue;
            }

            foreach ($permissionCodes as $permissionCode) {
                $permissionId = $permissions[$permissionCode] ?? null;
                if (! $permissionId) {
                    continue;
                }

                DB::table('permission_role')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $users = DB::table('users')->select(['id', 'role'])->get();
        foreach ($users as $user) {
            $roleCode = $user->role ?: User::ROLE_USER;
            $roleId = $roles[$roleCode] ?? $roles[User::ROLE_USER] ?? null;
            if (! $roleId) {
                continue;
            }

            DB::table('role_user')->insert([
                'role_id' => $roleId,
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
