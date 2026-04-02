@php
    $appName = \App\Models\AppSetting::getValue('app_name', 'TodoFlow');
    $themeColor = \App\Models\AppSetting::getValue('theme_color', '#7e57c2');
    $supportEmail = \App\Models\AppSetting::getValue('support_email', '');
    $brandLetters = strtoupper(substr($appName, 0, 1) . substr(str_replace(' ', '', $appName), 1, 1));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $themeColor }}">
    <title>{{ $title ?? $appName }}</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-flash="{{ session('success') }}">
<div class="loader-overlay" id="appLoader">
    <div class="loader-card">
        <div class="loader-logo">{{ $brandLetters }}</div>
        <div class="loader-title">Loading</div>
        <div class="loader-copy">Preparing your next task screen...</div>
    </div>
</div>

<div class="toast-wrap">
    <div class="toast-card" id="appToast">
        <div id="appToastText">Done</div>
    </div>
</div>

<div class="app-shell">
    <div class="app-topbar">
        <div class="app-container">
            <div class="app-topbar-row">
                <div class="brand-block">
                    <div class="brand-logo">{{ $brandLetters }}</div>
                    <div class="brand-copy">
                        <h1>{{ $appName }}</h1>
                        <p>Smart mobile task workspace</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    @auth
                        @if(auth()->user()->hasPermissionTo('admin.dashboard.view'))
                            <a href="{{ route('admin.dashboard') }}" class="icon-circle" aria-label="Admin"><i class="bi bi-speedometer2"></i></a>
                        @endif
                        <a href="{{ route('notifications.index') }}" class="icon-circle icon-circle-badge-wrap" aria-label="Notifications">
                            <i class="bi bi-bell"></i>
                            @php($unreadCount = auth()->user()->unreadNotificationsCount())
                            @if($unreadCount > 0)
                                <span class="icon-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('profile.edit') }}" class="profile-chip" aria-label="Profile">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</a>
                    @else
                        <a href="{{ route('login') }}" class="icon-circle" aria-label="Login"><i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>

            <div class="page-header">
                <h2 class="page-title">@yield('page_title', 'Workspace')</h2>
                <p class="page-subtitle">@yield('page_subtitle', 'Stay focused and move tasks faster.')</p>
                @if($supportEmail)
                    <p class="section-caption mt-1 mb-0">Support: {{ $supportEmail }}</p>
                @endif
            </div>

            <div class="offline-banner" id="offlineBanner">
                <i class="bi bi-wifi-off me-2"></i>No internet connection. Your last loaded screen stays available. Some actions will sync when you reconnect.
            </div>
        </div>
    </div>

    <div class="app-container">
        @yield('content')
    </div>
</div>

@auth
<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-ico"><i class="bi bi-house-door"></i></span>
        <span>Home</span>
    </a>
    <a href="{{ route('todos.index') }}" class="{{ request()->routeIs('todos.index') || request()->routeIs('todos.show') || request()->routeIs('todos.edit') ? 'active' : '' }}">
        <span class="nav-ico"><i class="bi bi-check2-square"></i></span>
        <span>Tasks</span>
    </a>
    <a href="{{ route('todos.create') }}" class="{{ request()->routeIs('todos.create') ? 'active' : '' }}">
        <span class="nav-ico"><i class="bi bi-plus-circle-fill"></i></span>
        <span>Add</span>
    </a>
    <a href="{{ route('calendar.index') }}" class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}">
        <span class="nav-ico"><i class="bi bi-calendar3"></i></span>
        <span>Calendar</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <span class="nav-ico"><i class="bi bi-person-circle"></i></span>
        <span>Profile</span>
    </a>
</nav>
@endauth

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js').catch(function () {});
    });
}
</script>
</body>
</html>
