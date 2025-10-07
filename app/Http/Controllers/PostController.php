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

    public function store(StorePostRequest $request)
    {
        // 1. Validasi input
        $request->validated();

        // 2. Proses data gambar Base64
        $imageBase64 = $request->input('cropped_image');
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
            'caption' => $request->input('caption'),
        ]);

        // 5. Redirect
        return redirect()
            ->route('profile.show', ['user' =>  Auth::user()->username])
            ->with('status', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->with('comments.user');

        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function like(Post $post)
    {
        /** @var \App\Models\User $user */
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

        return redirect()->route('profile.show', $user->username)->with('status', 'post deleterd');
    }

    public function edit(Post $post)
    {
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

        return redirect()->route('post.show', $post)->with('status', 'Post updated successfully!');
    }
}
