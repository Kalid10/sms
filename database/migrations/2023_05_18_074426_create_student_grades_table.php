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
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('gradable_id');
            $table->string('gradable_type');
            $table->foreignId('grade_scale_id')->nullable()->constrained()->cascadeOnDelete();
            $table->float('score')->nullable();
            $table->float('total_score')->nullable();
            $table->smallInteger('rank')->nullable();
            $table->char('conduct')->nullable();
            $table->smallInteger('attendance')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'gradable_id', 'gradable_type'], 'student_gradable_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
