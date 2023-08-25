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
        Schema::create('inventory_check_in_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('status');
            $table->timestamp('check_out_date');
            $table->timestamp('check_in_date')->nullable();
            $table->string('state')->default('checked_out');
            $table->unsignedBigInteger('recipient_user_id');
            $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('provider_user_id');
            $table->foreign('provider_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_check_in_outs');
    }
};
