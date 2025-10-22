<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1. Validasi input awal
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:32'],
            'username' => ['required', 'string', 'max:16', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // 2. Generate OTP dan waktu kedaluwarsa
        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        // 3. Simpan SEMUA data registrasi (termasuk OTP) ke dalam session
        //    Kita TIDAK membuat user di sini.
        $request->session()->put('registration_data', [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Password langsung di-hash
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        // 4. Kirim email OTP
        Mail::to($validated['email'])->send(new SendOtpMail((string)$otp));


        // 5. Redirect ke halaman verifikasi OTP
        return redirect()->route('otp.show')
            ->with('status', 'Please check your email for an OTP to complete your registration.');
    }


}
