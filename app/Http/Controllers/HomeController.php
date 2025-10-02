<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */ //
        $user = Auth::user();
        // 1. Dapatkan ID dari user yang sedang kita follow
        $followingIds = $user->following()->pluck('users.id');

        $followingIds->push($user->id);

        $posts = Post::with('user') // eageer loading
            ->whereIn('user_id', $followingIds)
            ->latest()  //shorcut buat orderby(created_at, desc)
            ->paginate(10);

        // dd($posts);

        return view('home', [
            'posts' => $posts
        ]);
    }
}
