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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('entity_id');
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('entity_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('batch_session_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->longText('body');
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
