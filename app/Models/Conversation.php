<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    /**
     * Relasi ke User (peserta obrolan).
     * Sebuah obrolan (Conversation) bisa memiliki banyak peserta (User).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relasi ke Message (isi obrolan).
     * Sebuah obrolan (Conversation) bisa memiliki banyak pesan (Message).
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}