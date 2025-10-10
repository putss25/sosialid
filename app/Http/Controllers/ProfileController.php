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

    // ✅ Batasi posts yang di-load (ambil 30 terakhir saja)
    $user->load(['posts' => function($query) {
        $query->latest()->take(30);
    }]);

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

    public function update(UpdateProfileRequest $request)
    {

        $user = Auth::user();
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar !== 'images/default-avatar.png') {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = "/{$path}";
        }

        $user->update($validatedData);

        return redirect()->route('profile.edit')->with('status', 'profile update success');
    }

    public function follow(User $User)
    {

        $user = Auth::user();
        $user->following()->attach($User);
        return back()->with('status', 'You are now following bro' . $User->username);
    }
    public function unfollow(User $User)
    {
        $user = Auth::user();
        $user->following()->detach($User);
        return back()->with('status', 'You are now unfollowing bro' . $User->username);
    }
}
