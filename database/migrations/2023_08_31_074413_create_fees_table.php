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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->float('amount');
            $table->string('currency')->default('ETB');
            $table->string('status')->default('active');
            $table->string('target_user_type');
            $table->timestamp('due_date');
            $table->bigInteger('feeable_id');
            $table->string('feeable_type');
            $table->json('details')->nullable();
            $table->foreignId('penalty_id')->nullable()->constrained('penalties')->cascadeOnDelete();
            $table->foreignId('level_category_id')->constrained('level_categories')->cascadeOnDelete();
            $table->boolean('is_student_tuition_fee')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
