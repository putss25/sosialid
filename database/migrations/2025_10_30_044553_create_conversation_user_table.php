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
        // Ini adalah tabel "Perantara" (Pivot)
        // Isinya adalah siapa saja "Peserta" di setiap "Ruang Obrolan"
        Schema::create('conversation_user', function (Blueprint $table) {
            // Menghubungkan ke tabel 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Menghubungkan ke tabel 'conversations'
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');

            // Kita set 'primary key' gabungan agar satu user
            // tidak bisa join ke obrolan yang sama dua kali
            $table->primary(['user_id', 'conversation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_user');
    }
};