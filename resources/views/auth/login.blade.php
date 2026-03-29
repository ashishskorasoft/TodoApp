@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-xl-4 col-lg-5 col-md-7">
        <div class="app-card section-card">
            <div class="text-center mb-4">
                <div class="brand-icon mx-auto mb-3">TF</div>
                <h2 class="fw-bold mb-1">Welcome Back</h2>
                <p class="text-muted mb-0">Sign in to access your task workspace.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                        <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                <div class="text-center mt-4">
                    <span class="text-muted">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="fw-semibold ms-1" style="color: var(--app-primary);">Register</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection