<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'notes',
        'priority',
        'status',
        'due_at',
        'labels',
        'is_archived',
        'reminder_minutes_before',
        'repeat_type',
        'repeat_interval',
        'repeat_weekdays',
        'last_repeated_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'labels' => 'array',
            'is_archived' => 'boolean',
            'repeat_weekdays' => 'array',
            'last_repeated_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(TodoChecklistItem::class)->orderBy('position');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(TodoNotification::class)->latest();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('is_archived', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')
            ->whereNotNull('due_at')
            ->where('due_at', '<', now());
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_at', now()->toDateString());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')
            ->whereDate('due_at', '>', now()->toDateString());
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'completed' && $this->due_at && $this->due_at->isPast();
    }

    public function getHasRecurrenceAttribute(): bool
    {
        return !empty($this->repeat_type);
    }
}
