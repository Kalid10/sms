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
        Schema::create('ai_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->longText('content');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_plan_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('batch_session_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('model');
            $table->string('source')->default('open_ai');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_notes');
    }
};
