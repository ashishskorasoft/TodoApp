<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderPreferenceController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar', [TodoController::class, 'calendar'])->name('calendar.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/notifications/feed', [NotificationController::class, 'feed'])->name('notifications.feed');
    Route::post('/notifications/subscribe', [NotificationController::class, 'subscribe'])->name('notifications.subscribe');
    Route::post('/notifications/test', [NotificationController::class, 'test'])->name('notifications.test');

    Route::get('/settings/reminders', [ReminderPreferenceController::class, 'edit'])->name('settings.reminders.edit');
    Route::patch('/settings/reminders', [ReminderPreferenceController::class, 'update'])->name('settings.reminders.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/todos/bulk-update', [TodoController::class, 'bulkUpdate'])->name('todos.bulk-update');
    Route::post('/todos/{todo}/quick-toggle', [TodoController::class, 'quickToggle'])->name('todos.quick-toggle');
    Route::post('/todos/{todo}/archive', [TodoController::class, 'archive'])->name('todos.archive');
    Route::post('/todos/{todo}/restore', [TodoController::class, 'restore'])->name('todos.restore');
    Route::resource('todos', TodoController::class);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleManagementController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
        Route::patch('/roles/{role}', [RoleManagementController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
