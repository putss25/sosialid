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
        // Ini adalah tabel untuk "Ruang Obrolan"
        Schema::create('conversations', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap ruang obrolan
            $table->timestamps(); // Kapan obrolan ini dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};