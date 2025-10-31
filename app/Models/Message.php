<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan use HasMany

class Message extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (PENTING!).
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'parent_message_id', // <-- TAMBAHKAN INI
    ];

    /**
     * Relasi ke User (siapa pengirimnya).
     * Sebuah pesan (Message) dimiliki oleh satu pengirim (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Conversation (ruang obrolannya).
     * Sebuah pesan (Message) dimiliki oleh satu obrolan (Conversation).
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    // -- V V V TAMBAHKAN DUA FUNGSI RELASI INI V V V --

    /**
     * Relasi ke pesan induk (jika ini adalah balasan).
     */
    public function parentMessage(): BelongsTo
    {
        // Merelasikan ke model 'Message' itu sendiri menggunakan 'parent_message_id'
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    /**
     * Relasi ke balasan (jika ini adalah pesan induk).
     */
    public function replies(): HasMany
    {
        // Merelasikan ke model 'Message' itu sendiri menggunakan 'parent_message_id'
        return $this->hasMany(Message::class, 'parent_message_id');
    }
    // -- ^ ^ ^ BATAS PENAMBAHAN ^ ^ ^ --
    
} // <-- Tambahkan sebelum kurung penutup class