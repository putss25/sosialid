<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="text-xl font-semibold text-gray-700">
                    <a href="/">Sosmed App</a>
                </div>
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="/login" class="text-gray-600 hover:text-gray-800">Login</a>
                        <a href="/register" class="text-gray-600 hover:text-gray-800">Register</a>
                    @endguest

                    @auth
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
 bvgcrdefwxs
</body>

</html>
