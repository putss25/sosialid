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
        Schema::table('messages', function (Blueprint $table) {
            // Tambahkan kolom baru 'parent_message_id'
            // Foreign key ke ID di tabel 'messages' itu sendiri
            // Nullable (pesan biasa tidak punya induk)
            // constrained('messages') -> merujuk ke tabel 'messages'
            // onDelete('set null') -> jika pesan induk dihapus, ID induk di balasan jadi NULL (tapi balasan tidak ikut hilang)
            $table->foreignId('parent_message_id')->nullable()->constrained('messages')->onDelete('set null')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['parent_message_id']);
            // Hapus kolom
            $table->dropColumn('parent_message_id');
        });
    }
};