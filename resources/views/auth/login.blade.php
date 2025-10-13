@extends('layouts.guest')

@section('content')
    <div class="flex min-h-screen">
        <!-- Left Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 text-foreground">
            <div class="w-full max-w-md space-y-8">
                <!-- Header -->
                <div class="text-left">
                    <div class="flex items-center space-x-2 mb-8">
                        <div class="w-10 h-10 bg-gradient-to-br rounded-lg flex items-center justify-center">
                            <img src="/images/LogoSnapishort.svg" alt="">
                        </div>
                        <img src="/images/snapi.svg" class="h-5" alt="">
                    </div>

                    <h1 class="text-3xl font-bold mt-6">Welcome to Snapi</h1>
                    <p class="text-gray-500 mt-2">Connect with friends and share your moments</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium  mb-2">Email</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                placeholder="example@mail.com"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium  mb-2">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required placeholder="8+ strong character"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <!-- Error Messages -->
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember Me</label>
                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full px-6 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all">
                            Sign in to Snapi
                        </button>
                    </div>
                </form>


                <p class="text-sm text-gray-500 mb-2">
                    Already have an account? <a href="/register" class="text-blue-600 hover:underline">Sign in</a>
                </p>
            </div>
        </div>

        <!-- Right Side - Hero Section -->
        <div
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary via-secondary to-accent items-center justify-center p-12 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-10 right-10 w-20 h-20 bg-cyan-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute bottom-20 left-10 w-32 h-32 bg-purple-400 rounded-full opacity-20 blur-2xl"></div>

            <!-- Content -->
            <div class="relative z-10 text-white max-w-lg">
                <div class="w-16 h-16 p-4 bg-primary rounded-full flex items-center justify-center mb-8 shadow-lg">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-quote-icon lucide-quote"><path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/><path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/></svg>
                </div>

                <h2 class="text-5xl font-bold mb-6 leading-tight">
                    Share Your<br />Story.
                </h2>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-white/20">
                    <p class="text-white/90 text-lg leading-relaxed italic">
                        "Snapi telah mengubah cara saya terhubung dengan teman dan keluarga. Platform yang luar biasa untuk
                        berbagi momen berharga!"
                    </p>
                </div>

                <div class="flex items-center space-x-4">
                    <p class="text-xl p-2  rounded-full border-2 border-white shadow-lg">PN</p>
                    <div>
                        <p class="font-semibold text-white">Pahril Nurfaisah</p>
                        <p class="text-white/70 text-sm">DR. Content Creator</p>
                    </div>
                </div>
            </div>

            <!-- Floating Cards Animation -->
            <div
                class="absolute top-1/4 right-20 bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 shadow-xl transform rotate-6 hover:rotate-3 transition-transform">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-red-500 rounded-full"></div>
                    <div class="text-white">
                        <div class="w-20 h-2 bg-white/40 rounded mb-1"></div>
                        <div class="w-16 h-2 bg-white/30 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
