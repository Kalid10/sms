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
        Schema::create('school_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('type');
            $table->json('tags')->nullable();
            $table->foreignId('school_year_id')->constrained('school_years')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_schedules');
    }
};
