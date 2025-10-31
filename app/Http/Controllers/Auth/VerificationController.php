<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{


    public function show(Request $request)
    {


        // Jika pengguna mencoba mengakses halaman ini tanpa data registrasi di session,
        // kembalikan mereka ke halaman register.
        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric|digits:6']);

        // Ambil data registrasi dari session
        $registrationData = $request->session()->get('registration_data');

        // Jika data session tidak ada (misal, kedaluwarsa atau dibuka di browser lain)
        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['otp_code' => 'Registration session has expired. Please try again.']);
        }

        // Cek apakah OTP salah
        if ($registrationData['otp'] != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'The provided OTP is incorrect.']);
        }

        // Cek apakah OTP kedaluwarsa
        if (now()->gt($registrationData['expires_at'])) {
            return back()->withErrors(['otp_code' => 'The OTP has expired. Please try again.']);
        }

        // --- JIKA SEMUA PEMERIKSAAN BERHASIL ---

        // 1. Buat user baru di database
        $user = User::create([
            'name' => $registrationData['name'],
            'username' => $registrationData['username'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'email_verified_at' => now(), // Langsung set sebagai terverifikasi
        ]);

        // 2. Hapus data sementara dari session
        $request->session()->forget('registration_data');

        // 3. Login-kan user yang baru dibuat
        Auth::login($user);

        // 4. Redirect ke homepage
        return redirect()->route('home')->with('status', 'Welcome! Your account has been created and verified.');
    }

    public function resend(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');
        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['otp_code' => 'Registration session has expired.']);
        }

        // Generate OTP dan expiry baru
        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinute(5);

        // Update data di session
        $registrationData['otp'] = $otp;
        $registrationData['expires_at'] = $expiresAt;
        $request->session()->put('registration_data', $registrationData);

        // Kirim ulang email
        Mail::to($registrationData['email'])->send(new SendOtpMail((string)$otp, $expiresAt));

        return back()->with('status', 'A new OTP has been sent to your email address.');
    }
}
