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
        Schema::create('student_tuition_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_tuition_id')->constrained('student_tuitions')->cascadeOnDelete();
            $table->foreignId('penalty_id')->constrained('penalties')->cascadeOnDelete();
            $table->float('amount');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tuition_penalties');
    }
};
