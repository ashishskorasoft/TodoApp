<?php

namespace App\Http\Controllers;

use App\Models\BrowserPushSubscription;
use App\Models\TodoNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasPermissionTo('notifications.view_own'), 403);

        $notifications = $request->user()->todoNotifications()->paginate(20);
        $pushEnabled = (bool) optional($request->user()->reminderPreference)->push_enabled;
        $activeDevices = $request->user()->browserPushSubscriptions()->where('is_active', true)->count();

        return view('notifications.index', compact('notifications', 'pushEnabled', 'activeDevices'));
    }

    public function markRead(Request $request, int $notification): RedirectResponse
    {
        abort_unless($request->user()->hasPermissionTo('notifications.view_own'), 403);

        $model = $request->user()->todoNotifications()->findOrFail($notification);
        $model->update(['read_at' => now()]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function subscribe(Request $request): JsonResponse
    {
        abort_unless($request->user()->hasPermissionTo('notifications.manage') || $request->user()->hasPermissionTo('notifications.view_own'), 403);

        $validated = $request->validate([
            'device_token' => ['required', 'string', 'max:120'],
            'permission' => ['nullable', 'string', 'in:default,denied,granted'],
            'endpoint' => ['nullable', 'string'],
            'public_key' => ['nullable', 'string'],
            'auth_token' => ['nullable', 'string'],
            'content_encoding' => ['nullable', 'string', 'max:50'],
            'user_agent' => ['nullable', 'string'],
        ]);

        $subscription = BrowserPushSubscription::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'device_token' => $validated['device_token'],
            ],
            [
                'permission' => $validated['permission'] ?? 'default',
                'endpoint' => $validated['endpoint'] ?? null,
                'public_key' => $validated['public_key'] ?? null,
                'auth_token' => $validated['auth_token'] ?? null,
                'content_encoding' => $validated['content_encoding'] ?? null,
                'user_agent' => $validated['user_agent'] ?? $request->userAgent(),
                'is_active' => ($validated['permission'] ?? 'default') === 'granted',
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'ok' => true,
            'message' => $subscription->is_active
                ? 'Browser notifications enabled on this device.'
                : 'Notification preference saved for this device.',
            'device_count' => $request->user()->browserPushSubscriptions()->where('is_active', true)->count(),
        ]);
    }

    public function feed(Request $request): JsonResponse
    {
        abort_unless($request->user()->hasPermissionTo('notifications.view_own'), 403);

        $deviceToken = (string) $request->query('device_token', '');
        if ($deviceToken !== '') {
            $request->user()->browserPushSubscriptions()
                ->where('device_token', $deviceToken)
                ->update(['last_seen_at' => now(), 'is_active' => true]);
        }

        $preference = $request->user()->reminderPreference;
        if (! $preference || ! $preference->push_enabled) {
            return response()->json(['items' => []]);
        }

        $items = $request->user()->todoNotifications()
            ->whereNull('delivered_at')
            ->latest()
            ->limit(10)
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $ids = $items->pluck('id');
        TodoNotification::query()->whereIn('id', $ids)->update(['delivered_at' => now()]);

        return response()->json([
            'items' => $items->map(function (TodoNotification $notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'body' => $notification->body,
                    'url' => $notification->todo_id ? route('todos.show', $notification->todo_id) : route('notifications.index'),
                    'created_at' => $notification->created_at?->toIso8601String(),
                ];
            })->values(),
        ]);
    }

    public function test(Request $request): RedirectResponse
    {
        abort_unless($request->user()->hasPermissionTo('notifications.view_own'), 403);

        TodoNotification::create([
            'user_id' => $request->user()->id,
            'todo_id' => null,
            'type' => 'test',
            'title' => 'Test notification',
            'body' => 'Notifications are working for your TodoFlow account on this device.',
            'meta' => ['source' => 'manual_test'],
        ]);

        return back()->with('success', 'Test notification created.');
    }
}
