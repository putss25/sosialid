<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>



<body class="font-sans antialiased">
    {{-- SATU x-data UTAMA untuk mengontrol semua state --}}
    <div x-data="{
        sidebarOpen: false,
        createModalOpen: false,
        step: 1,
        imageUrl: null,
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageUrl = URL.createObjectURL(file);
                this.step = 2;
            }
        },
        closeModal() {
            this.createModalOpen = false;
            setTimeout(() => {
                this.step = 1;
                this.imageUrl = null;
            }, 300);
        }
    }" class="flex h-screen bg-gray-50">

        {{-- Sidebar (Kode Anda di sini sudah bagus) --}}
        <aside
            class="flex-shrink-0 w-64 flex flex-col border-r bg-white transition-all duration-300 md:translate-x-0 z-10"
            :class="{ '-translate-x-full': !sidebarOpen }">
            <div class="h-16 flex items-center justify-center flex-shrink-0">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">Sosmed</a>
            </div>
            <nav class="flex-grow flex flex-col text-gray-600">
                <a href="{{ route('home') }}"
                    class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-gray-800 font-semibold {{ request()->routeIs('home') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <x-phosphor-house-light class="w-5 h-5 mr-3" />
                    <span>Home</span>
                </a>
                <button @click="createModalOpen = true"
                    class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-gray-800 font-semibold {{ request()->routeIs('posts.create') ? 'bg-gray-100 text-gray-900' : '' }}">
                    <x-eva-plus-outline class="w-5 h-5 mr-3" />
                    <span>Create Post</span>
                </button>
                <a href="{{ route('profile.show', auth()->user()) }}"
                    class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-gray-800 font-semibold {{ request()->routeIs('profile.show', auth()->user()) ? 'bg-gray-100 text-gray-900' : '' }}">
                    <img class="w-5 h-5 mr-3 object-cover rounded-full" src="{{ Auth::user()->avatar }}"
                        alt="Your avatar">

                    <span>Profile</span>
                </a>

                {{-- Link Admin Kondisional --}}
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-3 mt-4 border-t hover:bg-gray-100 hover:text-gray-800 font-semibold">
                        {{-- <x-heroicon-o-shield-check class="w-5 h-5 mr-3" /> --}}
                        <span>Admin Panel</span>
                    </a>
                @endif

                <div class="mt-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-800 font-semibold">
                            {{-- <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-3" /> --}}
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- Main Content (Kode Anda sudah bagus) --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center p-4 bg-white border-b md:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                    {{-- <x-heroicon-o-bars-3 class="h-6 w-6" /> --}}
                </button>
                <div class="text-lg font-bold text-indigo-600">Sosmed</div>
                <div></div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                @yield('content')
            </main>
        </div>

        {{-- Scripts (Kode Anda sudah bagus) --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        @stack('scripts')

        {{-- Modal untuk Create Post --}}
        {{-- Atribut x-data DIHAPUS dari sini karena sudah dipindahkan ke atas --}}
        <div x-show="createModalOpen" @keydown.escape.window="closeModal()" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div @click.away="closeModal()" class="bg-white rounded-lg shadow-xl w-11/12 md:w-1/2 ">
                {{-- ... Seluruh isi modal Anda (kode Anda di sini sudah benar) ... --}}
                <div class="px-4 py-3 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-lg">Create new post</h3>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div x-show="step === 1" class="flex flex-col items-center justify-center text-center">
                            <svg class="w-16 h-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 010-2.828L14 8m3 12l-4-4m4 4v-4m0 4h-4m-6-4l.75-1.5a.5.5 0 01.88-.13L16 16m-4-4l4 4m0 0l4-4m-4 4v4m0-4h4" />
                            </svg>
                            <p class="mt-4 text-xl text-gray-600">Drag photos and videos here</p>
                            <label for="image-upload"
                                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">Select
                                from computer</label>
                            <input id="image-upload" name="image" type="file" class="hidden"
                                @change="handleFileSelect($event)" accept="image/*" required>
                        </div>
                        <div x-show="step === 2" class="flex flex-col md:flex-row md:space-x-4">
                            <div class="md:w-1/2 mb-4 md:mb-0">
                                <img :src="imageUrl" alt="Image preview"
                                    class="rounded-lg object-cover w-full h-64">
                            </div>
                            <div class="md:w-1/2">
                                <div class="flex items-center space-x-3 mb-4"><img src="{{ Auth::user()->avatar }}"
                                        alt="Your avatar" class="w-8 h-8 rounded-full object-cover"><span
                                        class="font-semibold text-sm">{{ Auth::user()->username }}</span></div>
                                <textarea name="caption" rows="6"
                                    class="w-full border rounded-lg p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Write a caption..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div x-show="step === 2" class="px-4 py-3 border-t text-right"><button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Share</button></div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
