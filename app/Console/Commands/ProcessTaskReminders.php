<?php

namespace App\Console\Commands;

use App\Models\ReminderPreference;
use App\Models\Todo;
use App\Models\TodoNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessTaskReminders extends Command
{
    protected $signature = 'tasks:process-reminders';
    protected $description = 'Create due soon, overdue, recurring recreated, and daily summary notifications';

    public function handle(): int
    {
        $now = now()->seconds(0);

        $this->createDueSoonNotifications($now);
        $this->createOverdueNotifications($now);
        $this->recreateRecurringTasks($now);
        $this->createDailySummaries($now);

        return self::SUCCESS;
    }

    protected function createDueSoonNotifications(Carbon $now): void
    {
        Todo::with(['user.reminderPreference'])
            ->where('status', 'pending')
            ->where('is_archived', false)
            ->whereNotNull('due_at')
            ->whereNotNull('reminder_minutes_before')
            ->get()
            ->each(function (Todo $todo) use ($now) {
                if (! $todo->user || ! $todo->user->reminderPreference?->in_app_enabled) {
                    return;
                }

                $pref = $todo->user->reminderPreference;
                if (! $pref->due_soon_enabled) {
                    return;
                }

                $reminderAt = $todo->due_at->copy()->subMinutes((int) $todo->reminder_minutes_before)->seconds(0);
                if (! $reminderAt->equalTo($now)) {
                    return;
                }

                $this->createNotification(
                    $todo->user_id,
                    $todo->id,
                    'due_soon',
                    'Task due soon',
                    "'{$todo->title}' is due at {$todo->due_at->format('h:i A')}",
                    ['due_at' => $todo->due_at?->toIso8601String(), 'minute_key' => $now->format('Y-m-d H:i')]
                );
            });
    }

    protected function createOverdueNotifications(Carbon $now): void
    {
        Todo::with(['user.reminderPreference'])
            ->where('status', 'pending')
            ->where('is_archived', false)
            ->whereNotNull('due_at')
            ->where('due_at', '<', $now)
            ->get()
            ->each(function (Todo $todo) use ($now) {
                if (! $todo->user || ! $todo->user->reminderPreference?->in_app_enabled) {
                    return;
                }

                $pref = $todo->user->reminderPreference;
                if (! $pref->overdue_enabled) {
                    return;
                }

                $this->createNotification(
                    $todo->user_id,
                    $todo->id,
                    'overdue',
                    'Task overdue',
                    "'{$todo->title}' is overdue. Open it now to update or complete it.",
                    ['due_at' => $todo->due_at?->toIso8601String()]
                );
            });
    }

    protected function recreateRecurringTasks(Carbon $now): void
    {
        Todo::with(['user.reminderPreference'])
            ->where('status', 'completed')
            ->whereNotNull('repeat_type')
            ->get()
            ->each(function (Todo $todo) use ($now) {
                if (! $todo->completed_at) {
                    return;
                }

                if ($todo->last_repeated_at && $todo->last_repeated_at->greaterThanOrEqualTo($todo->completed_at)) {
                    return;
                }

                $nextDueAt = $this->calculateNextDueAt($todo, $todo->due_at ?? $todo->completed_at);
                if (! $nextDueAt) {
                    return;
                }

                $newTodo = $todo->replicate(['status', 'completed_at', 'last_repeated_at', 'created_at', 'updated_at']);
                $newTodo->status = 'pending';
                $newTodo->completed_at = null;
                $newTodo->due_at = $nextDueAt;
                $newTodo->save();

                foreach ($todo->checklistItems as $item) {
                    $newTodo->checklistItems()->create([
                        'title' => $item->title,
                        'is_completed' => false,
                        'position' => $item->position,
                    ]);
                }

                $todo->update(['last_repeated_at' => $now]);

                if ($todo->user && $todo->user->reminderPreference?->recurring_recreated_enabled) {
                    $this->createNotification(
                        $todo->user_id,
                        $newTodo->id,
                        'recurring_recreated',
                        'Recurring task recreated',
                        "A new recurring task '{$newTodo->title}' is ready for {$nextDueAt->format('d M, h:i A')}.",
                        ['source_todo_id' => $todo->id, 'due_at' => $nextDueAt->toIso8601String()]
                    );
                }
            });
    }

    protected function createDailySummaries(Carbon $now): void
    {
        ReminderPreference::with('user')
            ->where('daily_summary_enabled', true)
            ->whereNotNull('daily_summary_time')
            ->get()
            ->each(function (ReminderPreference $preference) use ($now) {
                if (! $preference->user || ! $preference->in_app_enabled) {
                    return;
                }

                $summaryTime = Carbon::createFromFormat('H:i', $preference->daily_summary_time)->format('H:i');
                if ($summaryTime !== $now->format('H:i')) {
                    return;
                }

                $existing = TodoNotification::query()
                    ->where('user_id', $preference->user_id)
                    ->where('type', 'daily_summary')
                    ->whereDate('created_at', $now->toDateString())
                    ->exists();

                if ($existing) {
                    return;
                }

                $pending = $preference->user->todos()->where('status', 'pending')->where('is_archived', false)->count();
                $today = $preference->user->todos()->whereDate('due_at', $now->toDateString())->where('is_archived', false)->count();
                $overdue = $preference->user->todos()->where('status', 'pending')->where('is_archived', false)->whereNotNull('due_at')->where('due_at', '<', $now)->count();

                $this->createNotification(
                    $preference->user_id,
                    null,
                    'daily_summary',
                    'Daily summary',
                    "You have {$pending} pending tasks, {$today} due today, and {$overdue} overdue.",
                    ['pending' => $pending, 'today' => $today, 'overdue' => $overdue]
                );
            });
    }

    protected function createNotification(int $userId, ?int $todoId, string $type, string $title, string $body, array $meta = []): void
    {
        $query = TodoNotification::query()
            ->where('user_id', $userId)
            ->where('type', $type)
            ->where('todo_id', $todoId);

        if (isset($meta['minute_key'])) {
            $query->where('meta->minute_key', $meta['minute_key']);
        } elseif (isset($meta['due_at'])) {
            $query->where('meta->due_at', $meta['due_at']);
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        if ($query->exists()) {
            return;
        }

        TodoNotification::create([
            'user_id' => $userId,
            'todo_id' => $todoId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'meta' => $meta,
        ]);
    }

    protected function calculateNextDueAt(Todo $todo, Carbon $base): ?Carbon
    {
        return match ($todo->repeat_type) {
            'daily' => $base->copy()->addDay(),
            'monthly' => $base->copy()->addMonth(),
            'custom' => $base->copy()->addDays(max(1, (int) ($todo->repeat_interval ?? 1))),
            'weekly' => $this->nextWeeklyDate($base, (array) ($todo->repeat_weekdays ?? [])),
            default => null,
        };
    }

    protected function nextWeeklyDate(Carbon $base, array $weekdays): Carbon
    {
        $map = ['mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6, 'sun' => 0];
        $targetDays = collect($weekdays)->map(fn ($day) => $map[$day] ?? null)->filter()->values();

        if ($targetDays->isEmpty()) {
            return $base->copy()->addWeek();
        }

        $candidate = $base->copy()->addDay();
        for ($i = 0; $i < 14; $i++) {
            if ($targetDays->contains($candidate->dayOfWeek)) {
                return $candidate;
            }
            $candidate->addDay();
        }

        return $base->copy()->addWeek();
    }
}
