<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->loadCount(['posts', 'followers', 'following']);

        // AMBIL POSTINGAN SECARA TERPISAH DENGAN PAGINASI
        // Kita panggil method relasi posts() (dengan kurung) untuk mendapatkan query builder
        $posts = $user->posts()
            ->latest()
            ->paginate(12);

        $posts->LoadCount(['likes', 'comments']);
        // ✅ Cek following status tanpa load semua following
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()
                ->following()
                ->where('following_user_id', $user->id)
                ->exists();
        }

        return view('users.profile', [
            'user' => $user,
            'posts' => $posts,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();

        return view('users.edit', [
            'user' => $user,
        ]);
    }


    public function follow(User $User)
    {
        if (Auth::id() === $User->id) {
            return back()->with('notification', [
                'type' => 'error',
                'message' => 'You cant folowing yourself'
            ]);
        }
        $user = Auth::user();
        $user->following()->attach($User);
        return back()->with('notification', [
            'type' => 'success',
            'message' => 'You are folowing bro' . $User->username
        ]);
    }
    public function unfollow(User $User)
    {
        $user = Auth::user();
        $user->following()->detach($User);
        return back()->with('notification', [
            'type' => 'success',
            'message' => 'You are now unfollowing bro' . $User->username
        ]);
    }
}
