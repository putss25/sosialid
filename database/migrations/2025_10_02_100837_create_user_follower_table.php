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
        Schema::create('user_follower', function (Blueprint $table) {
            // user_id adalah ID orang yang me-follow
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // following_user_id adalah ID orang yang di-follow
            $table->foreignId('following_user_id')->constrained('users')->onDelete('cascade');

            // Mencegah duplikasi (seseorang tidak bisa follow orang yang sama 2x)
            $table->primary(['user_id', 'following_user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_follower');
    }
};
