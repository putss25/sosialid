<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */ //
        $user = Auth::user();
        // 1. Dapatkan ID dari user yang sedang kita follow
        $followingIds = $user->following()->pluck('users.id');

        $followingIds->push($user->id);

        $posts = Post::with('user') // eageer loading
        ->withCount(['likes', 'comments'])
            ->whereIn('user_id', $followingIds)
            ->latest()  //shorcut buat orderby(created_at, desc)
            ->paginate(20);



        return view('home', [
            'posts' => $posts
        ]);
    }
}
