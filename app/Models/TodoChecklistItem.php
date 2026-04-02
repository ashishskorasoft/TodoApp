<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'todo_id',
        'title',
        'is_completed',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
