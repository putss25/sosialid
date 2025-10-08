@extends('layouts.guest')

@section('content')
<div class="max-w-xl mx-auto mt-10"
     x-data="{
        countdown: 60,
        canResend: false,
        init() {
            const timer = setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    clearInterval(timer);
                    this.canResend = true;
                }
            }, 1000);
        }
     }"
     x-init="init()"
>
    <div class="bg-background p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center ">Verify Your Email</h1>

        @if (session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <p class="text-center text-muted-foreground mb-6">We've sent a 6-digit OTP to your email address. Please enter the code below.</p>

        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            {{-- ... (kode form input OTP Anda tetap sama) ... --}}
            <div class="mb-4">
                <label for="otp_code" class="block text-sm font-medium text-[--color-text-secondary]">OTP Code</label>
                <input type="text" name="otp_code" id="otp_code" class="w-full mt-1 px-4 py-2 border ..." required autofocus maxlength="6">
                @error('otp_code')<p class="text-destructive text-xs mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mt-6"><button type="submit" class="w-full bg-primary p-1 ">Verify Account</button></div>
        </form>

        {{-- TOMBOL KIRIM ULANG OTP --}}
        <div class="text-center mt-4 text-sm">
            {{-- Tampil saat countdown berjalan --}}
            <span x-show="!canResend" class="text-[--color-text-secondary]">
                Didn't receive the code? <span class="font-bold" x-text="'Resend in ' + countdown + 's'"></span>
            </span>

            {{-- Tampil saat countdown selesai --}}
            <form action="{{ route('otp.resend') }}" method="POST" x-show="canResend" style="display: none;">
                @csrf
                <p class="text-secondary-foreground]">
                    Didn't receive the code?
                    <button type="submit" class="font-semibold text-primary hover:underline">Resend OTP</button>
                </p>
            </form>
        </div>

    </div>
</div>
@endsection
