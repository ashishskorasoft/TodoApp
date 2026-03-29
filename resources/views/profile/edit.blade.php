@extends('layouts.app')

@section('page_title', 'Profile Settings')
@section('page_subtitle', 'Manage your account information and security settings.')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="app-card profile-card h-100">
            <div class="d-flex flex-column align-items-center text-center">
                <div class="profile-avatar mb-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="app-card section-card mb-4">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="app-card section-card mb-4">
            @include('profile.partials.update-password-form')
        </div>

        <div class="app-card section-card">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection