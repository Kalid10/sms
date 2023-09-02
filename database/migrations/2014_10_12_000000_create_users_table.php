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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('profile_image')->nullable();
            $table->string('gender');
            $table->date('date_of_birth')->nullable();
            $table->string('type');
            $table->boolean('is_blocked')->default(0);
            $table->foreignId('address_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->json('fcm_tokens')->nullable();
            $table->string('password');
            $table->boolean('active_status')->default(0); // Chatify
            $table->string('avatar')->default(config('chatify.user_avatar.default')); // Chatify
            $table->boolean('dark_mode')->default(0); // Chatify
            $table->string('messenger_color')->nullable(); // Chatify
            $table->integer('openai_daily_usage')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
