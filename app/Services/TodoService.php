<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TodoService
{
    public function preparePayload(Request $request): array
    {
        $data = $request->validated();
        $labels = collect(explode(',', (string) $request->input('labels', '')))
            ->map(fn ($label) => trim((string) $label))
            ->filter()
            ->values()
            ->all();

        $data['labels'] = $labels;
        $data['repeat_interval'] = $data['repeat_type'] === 'custom' ? ($data['repeat_interval'] ?? 1) : null;
        $data['repeat_weekdays'] = $data['repeat_type'] === 'weekly' ? array_values($data['repeat_weekdays'] ?? []) : [];
        $data['completed_at'] = ($data['status'] ?? 'pending') === 'completed' ? ($data['completed_at'] ?? now()) : null;

        $dueAt = $request->input('due_at');
        $dueDate = $request->input('due_date');
        $dueHour = $request->input('due_hour');
        $dueMinute = $request->input('due_minute');
        $duePeriod = strtoupper((string) $request->input('due_period', 'AM'));

        if (! $dueAt && $dueDate && $dueHour !== null && $dueMinute !== null) {
            $hour = (int) $dueHour;
            if ($duePeriod === 'PM' && $hour < 12) {
                $hour += 12;
            } elseif ($duePeriod === 'AM' && $hour === 12) {
                $hour = 0;
            }

            $data['due_at'] = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %02d:%02d', $dueDate, $hour, (int) $dueMinute));
        } elseif (! $dueDate) {
            $data['due_at'] = null;
        }

        unset($data['due_date'], $data['due_hour'], $data['due_minute'], $data['due_period']);

        return $data;
    }

    public function createForUser($user, array $payload, array $subtasks = []): Todo
    {
        $todo = $user->todos()->create($payload);
        $this->syncChecklistItems($todo, $subtasks);

        return $todo->fresh(['checklistItems']);
    }

    public function updateTodo(Todo $todo, array $payload, array $subtasks = []): Todo
    {
        $todo->update($payload);
        $this->syncChecklistItems($todo, $subtasks);

        return $todo->fresh(['checklistItems']);
    }

    public function toggleCompletion(Todo $todo): Todo
    {
        $isCompleted = $todo->status === 'completed';
        $todo->update([
            'status' => $isCompleted ? 'pending' : 'completed',
            'completed_at' => $isCompleted ? null : now(),
        ]);

        return $todo->fresh();
    }

    public function archive(Todo $todo): void
    {
        $todo->update(['is_archived' => true]);
    }

    public function restore(Todo $todo): void
    {
        $todo->update(['is_archived' => false]);
    }

    public function bulkUpdate($query, string $action): int
    {
        return match ($action) {
            'complete' => $query->update(['status' => 'completed', 'completed_at' => now()]),
            'archive' => $query->update(['is_archived' => true]),
            'restore' => $query->update(['is_archived' => false]),
            'delete' => $query->delete(),
            default => 0,
        };
    }

    public function syncChecklistItems(Todo $todo, array $items): void
    {
        $todo->checklistItems()->delete();

        collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->each(function (string $title, int $index) use ($todo) {
                $todo->checklistItems()->create([
                    'title' => $title,
                    'position' => $index + 1,
                ]);
            });
    }
}
