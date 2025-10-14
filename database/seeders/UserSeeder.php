<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(3)->create();

        // 2. Buat 1 user spesifik yang memiliki 3 post
        User::factory()
            ->has(Post::factory()->count(3), 'posts') // <-- INI KUNCINYA
            ->create([
                // Anda bisa override data default di sini jika mau
                'name' => 'Budi',
                'username' => 'budi_doremi',
                'email' => 'budi@example.com',
            ]);
    }
}
