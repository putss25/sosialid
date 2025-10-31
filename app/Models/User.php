<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
        'avatar',
        'otp_code',
        'otp_expires_at',   
        'is_admin',
        // 'website' sepertinya hilang dari $fillable Anda, 
        // tapi saya biarkan sesuai kode yang Anda berikan.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_admin' => 'boolean',

        ];
    }

    public function getAvatarAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return asset('images/default-avatar.png');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follower', 'user_id', 'following_user_id');
    }
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follower',  'following_user_id', 'user_id');
    }
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // ==========================================================
    // == V V V INI FUNGSI BARU UNTUK FITUR DM V V V ==
    // ==========================================================
    /**
     * Relasi ke Conversation (obrolan yang diikuti user).
     * Seorang user (User) bisa bergabung di banyak obrolan (Conversation).
     */
    public function conversations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Conversation::class);
    }
    // ==========================================================
    
} // <-- Ditambahkan sebelum kurung kurawal penutup ini