<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7e57c2">
    <title>Create Account | TodoFlow</title>
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
                    <h1>Create your workspace</h1>
                    <p>Build a smooth mobile-first task system with reminders, calendar, and focused daily planning.</p>
                </div>
            </div>

            <div class="auth-showcase">
                <div class="auth-mini-phone">
                    <div class="auth-mini-header">
                        <div class="auth-mini-dot"></div>
                        <div class="auth-mini-dot"></div>
                        <div class="auth-mini-dot"></div>
                    </div>
                    <div class="auth-mini-card">
                        <div class="auth-mini-line"></div>
                        <div class="auth-mini-line short" style="margin-top: 8px;"></div>
                    </div>
                    <div class="auth-mini-card">
                        <div class="auth-mini-line"></div>
                        <div class="auth-mini-line short" style="margin-top: 8px;"></div>
                    </div>
                    <div class="auth-mini-card">
                        <div class="auth-mini-line"></div>
                        <div class="auth-mini-line short" style="margin-top: 8px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-card auth-form">
            <h2 class="page-title">Register</h2>
            <p class="page-subtitle">Set up your account and start organizing better.</p>

            <form method="POST" action="{{ route('register.store') }}" data-loader="true" class="app-form section-space">
                @csrf
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter your name">
                    @error('name')<div class="small-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email">
                    @error('email')<div class="small-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create password">
                    @error('password')<div class="small-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Create Account</button>
            </form>
            <div class="auth-footer">Already have an account? <a href="{{ route('login') }}">Login</a></div>
        </div>
    </div>
</div>
</body>
</html>
