<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function posts()
    {
        $posts = Post::with('user')->latest()->paginate(20);

         return view('admin.posts', [
            'posts' => $posts
        ]);
    }
    public function deletePost(Post $post)
    {
        $post->delete();



        return back()->with('status', 'Post has been deleted by admin');
    }
}
