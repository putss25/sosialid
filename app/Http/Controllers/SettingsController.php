<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }
/**
* Menyimpan postingan baru ke database (versi sederhana tanpa crop).
*/
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validateWithBag('updateProfile', [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama (kecuali default)
            if ($user->avatar && $user->avatar !== '/images/default-avatar.png') {
                $oldPath = $user->avatar;
                // dd($oldPath);


                // Buang base URL jika ada
                $oldPath = str_replace(url('/storage/'), '', $oldPath);
                $oldPath = str_replace('http://localhost:8000/storage/', '', $oldPath);
                $oldPath = str_replace('/storage/', '', $oldPath);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Upload avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] =  $path;
        } else {
            // Jangan update avatar jika tidak ada file baru
            unset($validatedData['avatar']);
        }

        $user->update($validatedData);

        return back()->with('notification', [
            'type' => 'success',
            'message' => 'Profile information updated successfully!'
        ]);
    }

    public function updatePassword(Request $request)
    {
     $validated = $request->validateWithBag('updatePassword', [
        'current_password' => ['required', 'current_password'],
        'new_password' => ['required', Password::min(8)->mixedCase(), 'confirmed']
     ]);

     $request->user()->update([
        'password' => Hash::make($validated['new_password']),
     ]);

     return back()->with('status-password', 'Password updated succesfully');
    }

}
