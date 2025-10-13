<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    public function users()
    {
        // 1. Ambil ID dari semua user yang sudah kita ikuti
        $followingIds = Auth::user()->following()->pluck('id');

        // 2. Tambahkan ID kita sendiri ke daftar 'pengecualian'
        $excludeIds = $followingIds->push(Auth::id());

        // 3. Ambil user yang TIDAK ADA di dalam daftar pengecualian,
        //    acak urutannya, dan batasi hasilnya (misal 12 user).
        $usersToDiscover = User::whereNotIn('id', $excludeIds)
                                ->inRandomOrder()
                                ->paginate(24);



        return view('explore.users', [
            'users' => $usersToDiscover,
        ]);
    }

     public function posts()
    {
        // 1. Ambil ID dari semua post yang sudah di-like oleh user
        $likedPostIds = Auth::user()->likes()->pluck('posts.id');

        // 2. Ambil ID dari semua post yang sudah dikomentari oleh user
        $commentedPostIds = Auth::user()->comments()->pluck('post_id')->unique();

        // 3. Gabungkan kedua daftar ID tersebut menjadi satu
        $excludePostIds = $likedPostIds->merge($commentedPostIds);

        // 4. Ambil postingan yang:
        //    - BUKAN milik user yang sedang login
        //    - TIDAK ADA di dalam daftar ID yang harus dikecualikan
        $postsToDiscover = Post::where('user_id', '!=', Auth::id())
                               ->whereNotIn('id', $excludePostIds)
                               ->latest() // Urutkan dari yang terbaru
                               ->paginate(18); // Paginasi, 18 post per halaman

        $postsToDiscover->load('user')->loadCount(['likes', 'comments']);

        return view('explore.posts', [
            'posts' => $postsToDiscover,
        ]);
    }
}
