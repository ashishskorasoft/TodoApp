<?php

namespace App\Models;

use App\Support\PermissionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function todoNotifications(): HasMany
    {
        return $this->hasMany(TodoNotification::class)->latest();
    }

    public function reminderPreference(): HasOne
    {
        return $this->hasOne(ReminderPreference::class);
    }

    public function browserPushSubscriptions(): HasMany
    {
        return $this->hasMany(BrowserPushSubscription::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->whereIn('role', [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    public function primaryRole(): ?Role
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('roles')) {
            return null;
        }

        if ($this->relationLoaded('roles')) {
            return $this->roles->sortBy('sort_order')->first();
        }

        return $this->roles()->orderBy('sort_order')->first();
    }

    public function primaryRoleCode(): string
    {
        return $this->primaryRole()?->code ?? (string) $this->role;
    }

    public function primaryRoleLabel(): string
    {
        $code = $this->primaryRoleCode();

        return static::roleOptions()[$code] ?? ucfirst(str_replace('_', ' ', $code));
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole([self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->hasPermissionTo('admin.dashboard.view');
    }

    public function hasRole(string $roleCode): bool
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('roles')) {
            return $this->role === $roleCode;
        }

        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(fn (Role $role) => $role->code === $roleCode);
        }

        return $this->roles()->where('code', $roleCode)->exists() || $this->role === $roleCode;
    }

    public function hasAnyRole(array $roleCodes): bool
    {
        foreach ($roleCodes as $roleCode) {
            if ($this->hasRole($roleCode)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionTo(string $permissionCode): bool
    {
        if ($this->hasRole(self::ROLE_SUPER_ADMIN)) {
            return true;
        }

        if ($this->relationLoaded('roles')) {
            foreach ($this->roles as $role) {
                if ($role->relationLoaded('permissions') && $role->permissions->contains(fn (Permission $permission) => $permission->code === $permissionCode)) {
                    return true;
                }
            }
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('roles') && \Illuminate\Support\Facades\Schema::hasTable('permissions') && \Illuminate\Support\Facades\Schema::hasTable('permission_role') && $this->roles()->whereHas('permissions', fn ($query) => $query->where('code', $permissionCode))->exists()) {
            return true;
        }

        $legacyRole = $this->role ?: self::ROLE_USER;

        return in_array($permissionCode, PermissionCatalog::matrix()[$legacyRole] ?? [], true);
    }

    public function hasAnyPermission(array $permissionCodes): bool
    {
        foreach ($permissionCodes as $permissionCode) {
            if ($this->hasPermissionTo($permissionCode)) {
                return true;
            }
        }

        return false;
    }

    public function assignRoleByCode(string $roleCode): void
    {
        $this->forceFill(['role' => $roleCode])->save();

        if (class_exists(Role::class) && \Schema::hasTable('roles') && \Schema::hasTable('role_user')) {
            $role = Role::query()->where('code', $roleCode)->first();
            if ($role) {
                $this->roles()->sync([$role->id]);
            }
        }
    }

    public function unreadNotificationsCount(): int
    {
        return $this->todoNotifications()->whereNull('read_at')->count();
    }

    public function activeTodosCount(): int
    {
        return $this->todos()->active()->count();
    }

    public static function roleOptions(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_USER => 'User',
        ];
    }
}
