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

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'newUsersToday' => $newUsersToday,
            'totalPosts' => $totalPosts,
            'newPostsToday' => $newPostsToday,
            'userGrowthData' => $userGrowthData
        ]);
    }

}
