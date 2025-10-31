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
        // Ini adalah tabel untuk "Isi Pesan"
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap pesan

            // Menghubungkan ke tabel 'conversations'
            // Jika ruang obrolan dihapus, semua pesannya ikut terhapus
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            
            // Menghubungkan ke tabel 'users' (siapa pengirimnya)
            // Jika user pengirim dihapus, pesannya akan di-set ke NULL
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->text('body'); // Kolom untuk isi teks pesannya
            $table->timestamp('read_at')->nullable(); // Penanda kapan pesan ini dibaca
            $table->timestamps(); // Kapan pesan ini dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};