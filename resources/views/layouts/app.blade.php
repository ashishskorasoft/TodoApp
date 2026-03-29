<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TaskFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    @auth
        <aside class="sidebar d-none d-lg-flex flex-column">
            <div class="brand-box">
                <div class="brand-icon">TF</div>
                <div class="brand-text">
                    <h4>TaskFlow</h4>
                    <small>Smart task workspace</small>
                </div>
            </div>

            <div class="nav-section-title">Workspace</div>
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('todos.index') }}" class="nav-link {{ request()->routeIs('todos.*') ? 'active' : '' }}">
                    My Todos
                </a>
                <a href="{{ route('todos.create') }}" class="nav-link {{ request()->routeIs('todos.create') ? 'active' : '' }}">
                    Add New Task
                </a>
            </nav>

            <div class="nav-section-title">Account</div>
            <nav class="nav flex-column">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    Profile Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <div class="offcanvas offcanvas-start mobile-drawer" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header border-bottom">
                <div class="brand-box mb-0">
                    <div class="brand-icon">TF</div>
                    <div class="brand-text">
                        <h4 id="mobileSidebarLabel">TaskFlow</h4>
                        <small>Smart task workspace</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <div class="nav-section-title">Workspace</div>
                <nav class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('todos.index') }}" class="nav-link {{ request()->routeIs('todos.*') ? 'active' : '' }}">
                        My Todos
                    </a>
                    <a href="{{ route('todos.create') }}" class="nav-link {{ request()->routeIs('todos.create') ? 'active' : '' }}">
                        Add New Task
                    </a>
                </nav>

                <div class="nav-section-title">Account</div>
                <nav class="nav flex-column">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">
                            Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    @endauth

    <main class="main-content">
        @auth
            <div class="topbar">
                <div class="d-flex justify-content-between align-items-start align-items-sm-center flex-column flex-sm-row gap-3">
                    <div class="d-flex align-items-start gap-3 w-100">
                        <button
                            class="btn btn-light-custom mobile-menu-btn d-lg-none"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mobileSidebar"
                            aria-controls="mobileSidebar"
                        >
                            ☰
                        </button>

                        <div class="flex-grow-1">
                            <h1 class="page-title mb-1">@yield('page_title', 'Welcome Back')</h1>
                            <p class="page-subtitle mb-0">@yield('page_subtitle', 'Manage your work with clarity and focus.')</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 gap-sm-3 topbar-user">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <div class="profile-avatar topbar-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </div>
        @endauth

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 app-alert">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>