<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <aside class="flex-shrink-0 w-64 flex flex-col border-r transition-all duration-300 md:translate-x-0"
            :class="{ '-translate-x-full': !sidebarOpen }">
            <div class="h-16 flex items-center justify-center flex-shrink-0 bg-white">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-gray-800">
                    Admin Panel
                </a>
            </div>
            <nav class="flex-grow flex flex-col bg-blue-500 text-gray-300">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.users.index') ? 'bg-gray-900 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A10.99 10.99 0 002.5 21h19.002a11 11 0 00-8.324-10.197z" />
                    </svg>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.posts.index') }}"
                    class="flex items-center px-4 py-3 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.posts.index') ? 'bg-gray-900 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 010-2.828L14 8m3 12l-4-4m4 4v-4m0 4h-4m-6-4l.75-1.5a.5.5 0 01.88-.13L16 16m-4-4l4 4m0 0l4-4m-4 4v4m0-4h4" />
                    </svg>
                    <span>Posts</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center p-4 bg-white border-b">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>

                <div></div>
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block h-8 w-8 rounded-full overflow-hidden focus:outline-none">
                        <img class="h-full w-full object-cover" src="{{ Auth::user()->avatar }}" alt="Your avatar">
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-10" x-cloak>
                        <div class="px-4 py-2 text-sm text-gray-700">{{ Auth::user()->username }}</div>
                        <a href="{{ route('home') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Go to
                            Site</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>

</html>
