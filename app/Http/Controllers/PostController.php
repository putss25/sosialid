<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image; // Perhatikan huruf 'a'

class PostController extends Controller
{
    /** @var \App\Models\User */
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'cropped_image' => 'required|string',
            'caption' => 'nullable|string|max:2200']);

        // 2. Proses data gambar Base64
        $imageBase64 = $request->cropped_image;
        // Pisahkan header dari data (misal: "data:image/jpeg;base64,")
        [$type, $imageBase64] = explode(';', $imageBase64);
        [, $imageBase64] = explode(',', $imageBase64);
        // Decode data Base64 menjadi data biner gambar
        $imageData = base64_decode($imageBase64);

        // Buat nama file unik
        $filename = 'posts/'.uniqid().'.jpg';

        // 3. Simpan file gambar ke storage
        Storage::disk('public')->put($filename, $imageData);

        // 4. Buat postingan di database
        Auth::user()->posts()->create([
            'image' => '/storage/'.$filename,
            'caption' => $request->caption,
        ]);

        // 5. Redirect
        return redirect()
            ->route('profile.show', ['user' =>  Auth::user()->username])
             ->with('notification', [
            'type' => 'success',
            'message' => 'Post created successfully'
        ]);
    }

    public function show(Post $post)
    {
         $post->load(['user', 'comments.user']) // Memperbaiki with() menjadi load() untuk memuat komentar
         ->loadCount(['likes', 'comments']);      // Menghitung jumlah likes secara efisien

        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function like(Post $post)
    {
        $user = Auth::user();

        $user->likes()->attach($post);

        return back();
    }

    public function unlike(Post $post)
    {
        $user = Auth::user();

        $user->likes()->detach($post);

        return back();
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();
        if ($user->id !== $post->user_id) {
            // Jika ID user yang login TIDAK SAMA DENGAN ID pemilik post,
            // hentikan proses dan tampilkan halaman error 403 (Forbidden).
            abort(403, 'This action is unauthorized.');
        }
        $post->delete();

        return redirect()->route('profile.show', $user->username)->with('notification', [
            'type' => 'error',
            'message' => 'Post Deleted'
        ]);
    }

    public function edit(Post $post)
    {
       if (Auth::user()->id !== $post->user_id && !Auth::user()->is_admin) {
            abort(403);
        }
        return view('posts.edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, Post $post)
    {
         if (Auth::user()->id !== $post->user_id && !Auth::user()->is_admin) {
            abort(403);
        }

        // Otorisasi: pastikan user boleh mengedit post ini

        $validated = $request->validate([
            'caption' => 'nullable|string|max:2200',
        ]);

        $post->update($validated);

        return redirect()->route('post.show', $post)->with('notification', [
            'type' => 'success',
            'message' => 'Post update successfully'
        ]);
    }
}
