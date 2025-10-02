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
        $user = auth()->user();
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar !== 'images/default-avarar.png') {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = "/storage/{$path}";
        }

        $user->update($validatedData);

        return redirect()->route('profile.edit')->with('status', 'profile update success');
    }
}
