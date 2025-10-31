<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Hapus 'use Illuminate\Support\Facades\Auth as FacadesAuth;' jika ada, karena duplikat

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'body' => 'required|string|max:2500',
        ]);
        
        // 2. Buat komentar dan simpan di variabel
        $newComment = $post->comments()->create([
            'user_id' => Auth::id(), // Gunakan Auth::id()
            'body' => $validated['body'],
        ]);

        // ==========================================================
        // == UBAHAN: Kembalikan JSON, bukan 'back()' ==
        // ==========================================================
        
        // 3. Kita perlu data user (avatar, username) untuk ditampilkan di frontend
        $newComment->load('user'); // Pastikan Model Comment punya relasi user()
        
        // 4. Ambil jumlah komentar terbaru
        $commentCount = $post->comments()->count();

        // 5. Kembalikan semua data sebagai JSON
        return response()->json([
            'newComment' => $newComment,
            'commentCount' => $commentCount
        ]);
    }
}