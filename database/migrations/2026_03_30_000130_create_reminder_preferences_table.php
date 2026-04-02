<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('push_enabled')->default(true);
            $table->boolean('in_app_enabled')->default(true);
            $table->boolean('email_enabled')->default(false);
            $table->boolean('daily_summary_enabled')->default(true);
            $table->string('daily_summary_time')->nullable();
            $table->unsignedInteger('default_reminder_minutes')->default(30);
            $table->boolean('due_soon_enabled')->default(true);
            $table->boolean('overdue_enabled')->default(true);
            $table->boolean('recurring_recreated_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_preferences');
    }
};
