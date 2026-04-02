<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'todo_id',
        'type',
        'title',
        'body',
        'read_at',
        'delivered_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'delivered_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
