<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->json('labels')->nullable()->after('due_at');
            $table->boolean('is_archived')->default(false)->after('labels');
            $table->integer('reminder_minutes_before')->nullable()->after('is_archived');
            $table->string('repeat_type')->nullable()->after('reminder_minutes_before');
            $table->integer('repeat_interval')->nullable()->after('repeat_type');
            $table->json('repeat_weekdays')->nullable()->after('repeat_interval');
            $table->timestamp('last_repeated_at')->nullable()->after('repeat_weekdays');
            $table->timestamp('completed_at')->nullable()->after('last_repeated_at');
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn([
                'labels',
                'is_archived',
                'reminder_minutes_before',
                'repeat_type',
                'repeat_interval',
                'repeat_weekdays',
                'last_repeated_at',
                'completed_at',
            ]);
        });
    }
};
