@extends('layouts.app')

@section('content')
    <div class="max-w-5xl h-full mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Settings</h1>

        <div x-data="{ tab: 'profile' }" class="flex flex-col md:flex-row md:space-x-8 ">
            <aside class="md:w-1/4 mb-8 md:mb-0 border-r  pr-2">
                <nav class="flex flex-col space-y-2">
                    <button @click="tab = 'profile'"
                        :class="{ 'bg-primary text-white': tab === 'profile', 'text-foreground hover:bg-muted-background': tab !== 'profile' }"
                        class="w-full text-left px-4 py-2 rounded-lg font-semibold">
                        Profile
                    </button>
                    <button @click="tab = 'appearance'"
                        :class="{ 'bg-primary text-white': tab === 'appearance', 'text-foreground hover:bg-muted-background': tab !== 'appearance' }"
                        class="w-full text-left px-4 py-2 rounded-lg font-semibold">
                        Appearance
                    </button>
                </nav>
            </aside>

            <main class="md:w-3/4 text-foreground">
                <section x-show="tab === 'profile'" class="bg-background p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold mb-4">Profile Information</h2>
                    <p class="text-muted-foreground mb-6">Update your account's profile information and email address.</p>
                    @if (session('status-profile'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            <span class="block sm:inline">{{ session('status-profile') }}</span>
                        </div>
                    @endif

                    {{-- Form untuk update nama & email --}}
                    <form action="{{ route('settings.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-foreground">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="mt-1 block w-full border  rounded-md shadow-sm p-1">
                            @error('name', 'updateProfile')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-foreground">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 block w-full border border-border rounded-md shadow-sm  p-1">
                            @error('email', 'updateProfile')
                                <p class="text-destructive text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-hover">
                                Save
                            </button>
                        </div>
                    </form>
                    <div class="mt-8 border-t border-border pt-8">
                        <h2 class="text-xl font-bold mb-4">Update Password</h2>
                        <p class="text-muted-foreground mb-6">Ensure your account is using a long, random password to stay
                            secure.
                        </p>

                        @if (session('status-password'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                role="alert">
                                <span class="block sm:inline">{{ session('status-password') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('settings.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="current_password"
                                    class="block text-sm font-medium text-muted-foreground">Current
                                    Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="mt-1 block w-full border rounded-md shadow-sm p-1" required>
                                @error('current_password', 'updatePassword')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="new_password" class="block text-sm font-medium text-muted-foreground">New
                                    Password</label>
                                <input type="password" name="new_password" id="new_password"
                                    class="mt-1 block w-full border rounded-md shadow-sm p-1" required>
                                @error('new_password', 'updatePassword')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation"
                                    class="block text-sm font-medium text-muted-foreground">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="mt-1 block w-full border rounded-md shadow-sm p-1" required>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-hover">
                                    Save Password
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <section x-show="tab === 'appearance'" class="bg-background p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Appearance</h2>
                    <p class="text-muted-foreground">Customize the look and feel of the application.</p>

                    <div class="mt-6">
                        <p class="text-gray-800 dark:text-text font-semibold mb-2">Theme</p>
                        <div class="flex space-x-4">
                            <button onclick="setTheme('light')" id="theme-light"
                                class="px-4 py-2 border rounded-lg">Light</button>
                            <button onclick="setTheme('dark')" id="theme-dark"
                                class="px-4 py-2 border rounded-lg">Dark</button>
                            <button onclick="setTheme('system')" id="theme-system"
                                class="px-4 py-2 border rounded-lg">System</button>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
