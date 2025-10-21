@extends('layouts.guest')

@section('content')
    <div class="flex min-h-screen">
        <!-- Left Side - Register Form -->
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

                    <h1 class="text-3xl font-bold mt-6">Create your account</h1>
                    <p class="text-gray-500 mt-2">Join Snapi and start sharing your story</p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="/register" class="space-y-5" >
                    @csrf

                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                        <div class="relative">
                            <input id="name" name="name" type="text" required placeholder="John Doe"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username Input -->
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">Username</label>
                        <div class="relative">
                            <input id="username" name="username" type="text" required autocomplete="off" aria-autocomplete="off"
                                placeholder="johndoe"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
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
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required placeholder="8+ strong character" autocomplete="new-password"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full px-6 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all">
                            Create Account
                        </button>
                    </div>
                </form>

                <p class="text-sm text-gray-500 text-center">
                    Already have an account? <a href="/login" class="text-blue-600 hover:underline">Sign in</a>
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
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>

                <h2 class="text-5xl font-bold mb-6 leading-tight">
                    Join Our<br />Community.
                </h2>

                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-white/20">
                    <p class="text-white/90 text-lg leading-relaxed">
                        Bergabunglah dengan ribuan pengguna yang telah menemukan cara baru untuk berbagi momen spesial
                        dan terhubung dengan orang-orang terdekat.
                    </p>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex -space-x-2">
                        <div
                            class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-pink-400 to-purple-500">
                        </div>
                        <div
                            class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-blue-400 to-cyan-500">
                        </div>
                        <div
                            class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-orange-400 to-red-500">
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-white">10,000+ Users</p>
                        <p class="text-white/70 text-sm">Already joined</p>
                    </div>
                </div>
            </div>

            <!-- Floating Cards Animation -->
            <div
                class="absolute top-1/4 right-20 bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 shadow-xl transform -rotate-6 hover:-rotate-3 transition-transform">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full"></div>
                    <div class="text-white">
                        <div class="w-20 h-2 bg-white/40 rounded mb-1"></div>
                        <div class="w-16 h-2 bg-white/30 rounded"></div>
                    </div>
                </div>
            </div>

            <div
                class="absolute bottom-1/4 left-20 bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 shadow-xl transform rotate-6 hover:rotate-3 transition-transform">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full"></div>
                    <div class="text-white">
                        <div class="w-20 h-2 bg-white/40 rounded mb-1"></div>
                        <div class="w-16 h-2 bg-white/30 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
