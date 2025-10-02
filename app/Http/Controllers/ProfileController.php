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
        $user->load('posts');
        return view('users.profile', [
            'user' => $user,
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
        /** @var \App\Models\User $user */
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
        /** @var \App\Models\User $user */

        $user = Auth::user();
        $user->following()->attach($User);
        return back()->with('status', 'You are now following bro' . $User->username);
    }
    public function unfollow(User $User)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->following()->detach($User);
        return back()->with('status', 'You are now unfollowing bro' . $User->username);
    }
}
