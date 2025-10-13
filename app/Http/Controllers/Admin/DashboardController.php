<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();

        // User daftar hari ini
        $newUsersToday =  User::whereDate('created_at', today())->count();

        // Total postingan
        $totalPosts = Post::count();

        $newPostsToday =  Post::whereDate('created_at', today())->count();

        // --- LOGIKA BARU UNTUK CHART ---
        $userGrowthData = [];
        for ($i = 6; $i >= 0; $i--) {
            // Ambil tanggal untuk setiap hari dalam 7 hari terakhir
            $date = today()->subDays($i);
            // Hitung jumlah user yang mendaftar pada tanggal tersebut
            $count = User::whereDate('created_at', $date)->count();

            // Simpan label (misal: "Oct 03") dan data (jumlah)
            $userGrowthData['labels'][] = $date->format('M d');
            $userGrowthData['data'][] = $count;
        }

         // 1. Ambil 5 Postingan Paling Populer (berdasarkan jumlah like)
    $popularPosts = Post::with('user') // Eager load data user-nya
                          ->withCount('likes') // Hitung jumlah relasi 'likes'
                          ->orderBy('likes_count', 'desc') // Urutkan berdasarkan 'likes_count'
                          ->take(5) // Ambil 5 teratas
                          ->get();

    // 2. Ambil 5 Pengguna Paling Aktif (berdasarkan jumlah post)
    $activeUsers = User::withCount('posts') // Hitung jumlah relasi 'posts'
                         ->orderBy('posts_count', 'desc') // Urutkan berdasarkan 'posts_count'
                         ->take(5) // Ambil 5 teratas
                         ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'newUsersToday' => $newUsersToday,
            'totalPosts' => $totalPosts,
            'newPostsToday' => $newPostsToday,
            'userGrowthData' => $userGrowthData,
            'popularPosts' => $popularPosts,
            'activeUsers' => $activeUsers,
        ]);
    }

}
