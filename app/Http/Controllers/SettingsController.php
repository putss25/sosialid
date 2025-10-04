<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        // Validated with bag agar memasukan error ke kantong nya karena ada 2 form di 1 page
        $validated = $request->validateWithBag('updateProfile',[
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ]
            ]);
            $user->update($validated);
            return back()->with('status-profile', 'Profile information has been updated!');
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
