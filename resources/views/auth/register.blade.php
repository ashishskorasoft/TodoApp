@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-xl-4 col-lg-5 col-md-7">
        <div class="app-card section-card">
            <div class="text-center mb-4">
                <div class="brand-icon mx-auto mb-3">TF</div>
                <h2 class="fw-bold mb-1">Create Account</h2>
                <p class="text-muted mb-0">Start organizing your daily work smarter.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your full name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Create password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>

                <div class="text-center mt-4">
                    <span class="text-muted">Already have an account?</span>
                    <a href="{{ route('login') }}" class="fw-semibold ms-1" style="color: var(--app-primary);">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection