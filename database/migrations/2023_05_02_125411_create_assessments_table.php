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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('maximum_point');
            $table->foreignId('assessment_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quarter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_plan_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->dateTime('due_date');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
