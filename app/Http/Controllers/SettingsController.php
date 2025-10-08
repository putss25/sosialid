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

    // Lakukan validasi langsung di sini menggunakan validateWithBag
    $validatedData = $request->validateWithBag('updateProfile', [
      'name' => ['required', 'string', 'max:255'],

        // TAMBAHKAN ATURAN BARU UNTUK USERNAME
        'username' => [
            'required',
            'string',
            'max:255',
            'alpha_dash', // Hanya boleh huruf, angka, strip (-), dan underscore (_)
            Rule::unique('users')->ignore($user->id), // Harus unik, kecuali untuk user ini sendiri
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

    // --- Logika upload avatar ---
    if ($request->hasFile('avatar')) {
        if ($user->avatar && $user->avatar != '/images/default-avatar.png' && Storage::disk('public')->exists(str_replace('/storage/', '', $user->avatar))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $validatedData['avatar'] = '/' . $path;
    }

    // --- Update data user ---
    $user->update($validatedData);

    return back()->with('notification', [
    'type' => 'success', // Varian notifikasi: 'success', 'error', 'info', 'warning'
    'message' => 'Profile information updated successfully!' // Isi pesan
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
