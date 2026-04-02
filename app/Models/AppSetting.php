<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
    ];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        return static::query()->where('setting_key', $key)->value('setting_value') ?? $default;
    }

    public static function putValue(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );
    }
}
