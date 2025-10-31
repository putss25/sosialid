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
        $isFollowing = Auth::user() ? Auth::user()->following->contains($user->id) : false;
        
        $user->loadCount(['posts', 'followers', 'following']);
        $user->load(['followers', 'following']); 

        // ==========================================================
        // == UBAHAN: Tambahkan withCount() untuk menghitung like & comment ==
        // ==========================================================
        $posts = $user->posts()
                    ->withCount(['likes', 'comments']) // <-- TAMBAHKAN INI
                    ->latest()
                    ->paginate(12);
        // ==========================================================

        return view('users.profile', [
            'user' => $user,
            'isFollowing' => $isFollowing,
            'posts' => $posts,
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
