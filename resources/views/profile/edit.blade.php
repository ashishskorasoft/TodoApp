@extends('layouts.app', ['title' => 'Profile | TodoFlow'])

@section('page_title', 'Profile')
@section('page_subtitle', 'Manage your account details with the same compact design system.')

@section('content')
<div class="app-card section-card section-space">
    <div class="profile-header">
        <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
            <h3 class="profile-name">{{ auth()->user()->name }}</h3>
            <p class="profile-email">{{ auth()->user()->email }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" data-loader="true" class="app-form">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" placeholder="Enter your name">
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" placeholder="Enter your email">
        </div>
        <div class="inline-fields">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" placeholder="Optional">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
            </div>
        </div>
        <div class="app-toolbar mt-3">
            <button class="btn btn-primary">Save Changes</button>
            <button type="submit" formaction="{{ route('logout') }}" formmethod="POST" class="btn btn-soft" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</button>
        </div>
    </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</div>

<div class="app-card section-card section-space">
    <div class="section-header">
        <div>
            <h3 class="section-title">Danger Zone</h3>
            <p class="section-caption">Delete your account permanently if you no longer need it.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('profile.destroy') }}" data-loader="true" onsubmit="return confirm('Delete your account permanently?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger-soft w-100">Delete Account</button>
    </form>
</div>
@endsection
