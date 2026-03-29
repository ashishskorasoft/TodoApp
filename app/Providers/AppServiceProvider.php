<?php

namespace App\Providers;

use App\Models\Todo;
use App\Policies\TodoPolicy;
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
        Paginator::useBootstrapFive();
    }
}