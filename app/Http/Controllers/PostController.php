<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /** @var \App\Models\User $user */

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $path = $request->file('image')->store('posts', 'public');

        $validatedData['image'] = "/storage/{$path}";

        $user->posts()->create($validatedData);

        return redirect()
            ->route('profile.show', ['user' => $user->username])
            ->with('status', 'Post created succesfully');
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
}
