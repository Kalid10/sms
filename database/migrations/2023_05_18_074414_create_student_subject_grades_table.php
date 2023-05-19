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
        Schema::create('student_subject_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_subject_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('gradable_id');
            $table->string('gradable_type');
            $table->foreignId('grade_scale_id')->constrained()->cascadeOnDelete();
            $table->float('score');
            $table->timestamps();

            $table->unique(['student_id', 'batch_subject_id', 'gradable_type', 'gradable_id'], 'student_batch_subject_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subject_grades');
    }
};
