<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7e57c2">
    <title>Login | TodoFlow</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="auth-shell">
    <div class="auth-container auth-layout">
        <div class="app-card auth-hero">
            <div class="auth-brand">
                <div class="brand-logo">TF</div>
                <div class="auth-brand-copy">
                    <h1>Welcome back</h1>
                    <p>Plan faster, focus better, finish more from one clean mobile-first workspace.</p>
                </div>
            </div>

            <div class="auth-showcase">
                <div class="auth-preview-list">
                    <div class="auth-preview-item">
                        <div class="auth-preview-icon">✓</div>
                        <div>
                            <h3>Today planning</h3>
                            <p>Quick mobile-first task flow with fast detail opening and compact actions.</p>
                        </div>
                    </div>
                    <div class="auth-preview-item">
                        <div class="auth-preview-icon">⏰</div>
                        <div>
                            <h3>Reminder ready</h3>
                            <p>Due soon, overdue, and recurring reminders from one clean workspace.</p>
                        </div>
                    </div>
                    <div class="auth-preview-item">
                        <div class="auth-preview-icon">📅</div>
                        <div>
                            <h3>Smart calendar</h3>
                            <p>Track upcoming work and manage your day with a polished task calendar view.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-card auth-form">
            <h2 class="page-title">Login</h2>
            <p class="page-subtitle">Secure sign in for your personal task flow.</p>

            <form method="POST" action="{{ route('login.store') }}" data-loader="true" class="app-form section-space">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email">
                    @error('email')<div class="small-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password">
                    @error('password')<div class="small-error">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="auth-footer">No account yet? <a href="{{ route('register') }}">Create one</a></div>
        </div>
    </div>
</div>
</body>
</html>
