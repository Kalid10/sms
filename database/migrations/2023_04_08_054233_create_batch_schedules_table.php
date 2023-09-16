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
        Schema::create('batch_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_subject_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->unique(['batch_id', 'school_period_id', 'day_of_week'], 'bs_bsi_spi_dow_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_schedules');
    }
};
