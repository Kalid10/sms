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
        Schema::create('batch_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('gradable_id');
            $table->string('gradable_type');
            $table->foreignId('grade_scale_id')->constrained()->cascadeOnDelete();
            $table->float('score');
            $table->smallInteger('rank')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'gradable_id', 'gradable_type'], 'student_gradable_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_grades');
    }
};
