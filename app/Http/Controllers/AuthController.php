<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->except('password'));
        }

        if (! $request->user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is inactive. Please contact an administrator.']);
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Welcome back.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $firstUser = User::count() === 0;
        $role = $firstUser ? User::ROLE_SUPER_ADMIN : User::ROLE_USER;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'is_active' => true,
        ]);

        $user->assignRoleByCode($role);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', $firstUser
            ? 'Account created. Admin access has been enabled for the first user.'
            : 'Account created successfully.');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
