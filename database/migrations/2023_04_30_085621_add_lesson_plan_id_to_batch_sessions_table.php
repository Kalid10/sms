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
        Schema::table('batch_sessions', function (Blueprint $table) {
            $table->foreignId('lesson_plan_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_sessions', function (Blueprint $table) {
            $table->dropForeign(['lesson_plan_id']);
            $table->dropColumn('lesson_plan_id');
        });
    }
};