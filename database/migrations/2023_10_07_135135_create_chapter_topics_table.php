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
        Schema::create('chapter_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_chapter_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->foreignId('start_page')->references('id')->on('book_pages')->cascadeOnDelete();
            $table->foreignId('end_page')->references('id')->on('book_pages')->cascadeOnDelete();
            $table->longText('summary')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_topics');
    }
};
