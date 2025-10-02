<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'body' => 'required|string|max:2500',
        ]);
        // 2. Buat komentar menggunakan relasi
        $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        // 3. Redirect kembali ke halaman sebelumnya
        return back()->with('status', 'Comment posted!');
    }
}
