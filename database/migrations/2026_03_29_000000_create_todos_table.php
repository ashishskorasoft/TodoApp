<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('notes')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->dateTime('due_at')->nullable();
            $table->json('labels')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->integer('reminder_minutes_before')->nullable();
            $table->string('repeat_type')->nullable();
            $table->integer('repeat_interval')->nullable();
            $table->json('repeat_weekdays')->nullable();
            $table->timestamp('last_repeated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('due_at');
            $table->index('is_archived');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
