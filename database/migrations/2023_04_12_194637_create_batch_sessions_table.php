<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->foreignId('batch_schedule_id')->constrained('batch_schedules')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('lesson_plan_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('scheduled'); // 'scheduled', 'in_progress', 'completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_sessions');
    }
};
