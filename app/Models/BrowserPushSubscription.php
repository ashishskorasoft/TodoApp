<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrowserPushSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_token',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
        'user_agent',
        'permission',
        'is_active',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
