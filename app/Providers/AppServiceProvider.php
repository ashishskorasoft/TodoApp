<?php

namespace App\Providers;

use App\Models\Todo;
use App\Policies\TodoPolicy;
use App\Support\PermissionCatalog;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Todo::class, TodoPolicy::class);

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        foreach (PermissionCatalog::all() as $permissionCode) {
            Gate::define($permissionCode, fn ($user) => $user->hasPermissionTo($permissionCode));
        }

        Paginator::useBootstrapFive();
    }
}
