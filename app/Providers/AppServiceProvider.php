<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // TAMBAHKAN KODE RATE LIMITER DI SINI
        RateLimiter::for('resend-otp', function (Request $request) {
            // Izinkan 1 kali percobaan per menit untuk setiap sesi email
            return Limit::perMinute(1)->by($request->session()->get('registration_data')['email'] ?? $request->ip());
        });
    }
}
