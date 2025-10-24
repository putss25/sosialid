<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Soasialid') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        // Function untuk apply theme
        function applyTheme(theme) {
            if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Function untuk set theme
        function setTheme(newTheme) {
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
            updateThemeButtons(newTheme);
        }

        // Function untuk update tampilan button
        function updateThemeButtons(currentTheme) {
            // Remove semua ring
            ['light', 'dark', 'system'].forEach(t => {
                const btn = document.getElementById('theme-' + t);
                if (btn) {
                    if (t === currentTheme) {
                        btn.classList.add('ring-2', 'ring-primary');
                    } else {
                        btn.classList.remove('ring-2', 'ring-primary');
                    }
                }
            });
        }

        // Apply theme saat page load
        const savedTheme = localStorage.getItem('theme') || 'system';
        applyTheme(savedTheme);

        // Update button state setelah page load
        document.addEventListener('DOMContentLoaded', function() {
            updateThemeButtons(savedTheme);
        });

        // Listen untuk system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const currentTheme = localStorage.getItem('theme') || 'system';
            if (currentTheme === 'system') {
                applyTheme('system');
            }
        });
    </script>
</head>

<body class="font-sans antialiased">
    <div x-data="{
        createModalOpen: false,
        step: 1,
        imageUrl: null,
        cropperInstance: null,
        isSubmitting: false,

       handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageUrl = URL.createObjectURL(file);
                this.step = 2;
                this.$nextTick(() => {
                    const image = document.getElementById('image-to-crop');
                    this.cropperInstance = new Cropper(image, {
                        aspectRatio: 3 / 4,
                        viewMode: 1,
                    });
                });
            }
        }, 

        cropAndProceed() {
            if (this.cropperInstance) {
                const croppedCanvas = this.cropperInstance.getCroppedCanvas({
                    width: 1080,
                    height: 1440,
                });
                const croppedImageBase64 = croppedCanvas.toDataURL('image/jpeg');
                this.imageUrl = croppedImageBase64;
                document.getElementById('cropped-image-data').value = croppedImageBase64;
                this.step = 3;
            }
        },

        closeModal() {
            this.createModalOpen = false;
            setTimeout(() => {
                this.step = 1;
                this.imageUrl = null;
                if (this.cropperInstance) {
                    this.cropperInstance.destroy();
                    this.cropperInstance = null;
                }
                document.getElementById('image-upload').value = '';
            }, 300);
        }
    }" class="flex h-screen bg-background">
        <x-notification />

        <aside class="hidden lg:flex flex-shrink-0  w-52 flex-col border-r border-border z-10">
            <div class="py-8 flex items-center  flex-shrink-0 px-4">
                <img src="/images/snapi.svg" class="w-[40%]" alt="">
            </div>

            <nav class="flex-grow flex flex-col text-muted-foreground text-xl gap-2">


                <a href="{{ route('home') }}"
                    class="flex items-center px-4 py-3 hover:text-foreground font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-house-icon lucide-house text-foreground mr-3">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path
                            d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span>Home</span>
                </a>
                <button @click="createModalOpen = true"
                    class="flex items-center px-4 py-3 hover:text-foreground font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus text-foreground mr-3">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    <span>Create</span>
                </button>
                <a href="{{ route('search.index') }}"
                    class="flex items-center px-4 py-3 hover:text-foreground font-semibold transition-all duration-200 {{ request()->routeIs('search.index') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-search-icon lucide-search text-foreground mr-3">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <span>Search</span>
                </a>
                <a href="{{ route('explore.posts') }}"
                    class="flex items-center px-4 py-3 hover:text-foreground font-semibold transition-all duration-200 {{ request()->routeIs('explore.posts') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-compass-icon lucide-compass text-foreground mr-3">
                        <path
                            d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    <span>Explore</span>
                </a>

                <a href="{{ route('profile.show', auth()->user()) }}"
                    class="flex items-center px-4 py-3 hover:text-foreground font-semibold transition-all duration-200 {{ request()->routeIs('profile.show', auth()->user()) ? 'bg-muted-background text-foreground' : '' }}">
                    <img class="w-6 h-6 mr-3 object-cover rounded-full" src="{{ Auth::user()->avatar }}"
                        alt="Your avatar">
                    <span>Profile</span>
                </a>


                <div class="mt-auto px-4 py-8">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block h-5 w-5  overflow-hidden focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu text-foreground">
                                <path d="M4 5h16" />
                                <path d="M4 12h16" />
                                <path d="M4 19h16" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                            class="absolute bottom-full  -translate-y-4 left-2 mt-2 w-48 bg-muted-background rounded-md shadow-xl z-10 text-base"
                            x-cloak>
                            {{-- <div class="px-4 py-3 text-foreground border-b border-border">
                                {{ Auth::user()->username }}</div> --}}
                            <a href="{{ route('settings.index') }}"
                                class=" block px-4 py-2 text-muted-foreground hover:bg-primary hover:text-white ">
                                Settings
                            </a>

                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-muted-foreground hover:bg-primary hover:text-white">

                                    Dashboard
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-muted-foreground hover:bg-primary hover:text-white">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="lg:hidden flex justify-between items-center p-3 px-5 bg-background border-b">
                <img src="/images/snapi.svg" class="w-[15%] max-w-[100px]" alt="">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block h-5 w-5  overflow-hidden focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu text-foreground">
                            <path d="M4 5h16" />
                            <path d="M4 12h16" />
                            <path d="M4 19h16" />
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute top-full  translate-y-0 right-0 mt-2 w-48 bg-muted-background rounded-md shadow-xl z-10 text-base"
                        x-cloak>
                        {{-- <div class="px-4 py-3 text-foreground border-b border-border">
                                {{ Auth::user()->username }}</div> --}}
                        <a href="{{ route('settings.index') }}"
                            class=" block px-4 py-2 text-muted-foreground hover:bg-primary hover:text-white ">
                            Settings
                        </a>

                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-2 text-muted-foreground hover:bg-primary hover:text-white">

                                Dashboard
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-4 py-2 text-muted-foreground hover:bg-primary hover:text-white">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto  pb-24 lg:pb-0">
                @yield('content')
            </main>
        </div>

        <nav
            class="lg:hidden fixed bottom-0 left-0 w-full bg-background border-t flex justify-around items-center z-20">

            <a href="{{ route('home') }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'text-foreground' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-house-icon lucide-house text-foreground mb-1">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path
                        d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>Home</span>
            </a>
            <a href="{{ route('search.index') }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 text-sm font-semibold {{ request()->routeIs('search.index') ? 'text-primary' : 'text-foreground' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-search-icon lucide-search text-foreground mb-1">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
                <span>Search</span>
            </a>
            <button @click="createModalOpen = true"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 text-sm font-semibold text-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus text-foreground mb-1">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                <span>Create</span>
            </button>
            <a href="{{ route('explore.posts') }}"
                class="flex flex-1 flex-col items-center px-4 py-3 hover:text-foreground font-semibold transition-all duration-200 {{ request()->routeIs('explore.posts') ? 'bg-muted-background text-foreground' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-compass-icon lucide-compass text-foreground ">
                    <path
                        d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <span>Explore</span>
            </a>
            <a href="{{ route('profile.show', auth()->user()) }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 text-sm font-semibold {{ request()->routeIs('profile.show', auth()->user()) ? 'text-primbg-primary' : 'text-gray-600' }}">
                <img class="w-6 h-6 mb-1 object-cover rounded-full" src="{{ Auth::user()->avatar }}"
                    alt="Your avatar">
                <span>Profile</span>
            </a>
        </nav>

        {{-- Modal untuk Create Post (Tidak ada perubahan di sini) --}}
        <div x-show="createModalOpen" @keydown.escape.window="closeModal()" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="closeModal()"
                class="bg-background rounded-lg shadow-xl w-11/12 md:w-1/2 lg:w-2/5 max-h-[90vh] overflow-y-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">
                <div class="px-4 py-3 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-lg"
                        x-text="step === 3 ? 'Write a caption' : (step === 2 ? 'Crop Image' : 'Create new post')"></h3>
                    <button x-show="step === 2" @click="cropAndProceed()"
                        class="text-sm font-bold text-primary hover:text-primary-hover">Next</button>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-800 text-2xl leading-none"
                        x-show="step !== 2">&times;</button>
                </div>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                    @submit="isSubmitting = true">
                    @csrf
                    <input type="hidden" name="cropped_image" id="cropped-image-data">
                    <div class="p-6">
                        <div x-show="step === 1" class="flex flex-col items-center justify-center text-center py-10">
                            <svg class="w-16 h-16 text-foreground mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xl  mb-2">Select an image to start</p>
                            <p class="text-sm text-muted-foreground mb-4">Drag photos here or click to browse</p>
                            <label for="image-upload"
                                class="px-6 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600 transition">
                                Select from computer
                            </label>
                            <input id="image-upload" name="image" type="file" class="hidden"
                                @change="handleFileSelect($event)" accept="image/*">
                        </div>
                        <div x-show="step === 2">
                            <div class="w-full">
                                <img id="image-to-crop" :src="imageUrl" class="block max-w-full max-h-[450px]">
                            </div>
                        </div>
                        <div x-show="step === 3" class="flex flex-col md:flex-row md:space-x-4">
                            <div class="md:w-1/2 mb-4 md:mb-0">
                                <img :src="imageUrl" alt="Image preview"
                                    class="rounded-lg object-cover ratio-4x3">
                            </div>
                            <div class="md:w-1/2">
                                <textarea name="caption" rows="6"
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Write a caption..."></textarea>
                                @error('caption')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div x-show="step === 3" class="px-4 py-3 border-t text-right flex justify-end">
                        <button type="button" @click="closeModal()"
                            class="px-6 py-2 bg-muted-background text-muted-foreground rounded-lg hover:background mr-2">
                            Cancel
                        </button>
                        <button type="submit" :disabled="isSubmitting"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition disabled:cursor-not-allowed flex flex-row items-center justify-center ">

                            {{-- Teks saat tidak submitting --}}
                            <span x-show="!isSubmitting">Share</span>

                            {{-- Teks/Spinner saat sedang submitting --}}
                            <span x-show="isSubmitting">
                                <svg width="24" height="24" class="stroke-white" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <style>
                                        .spinner_V8m1 {
                                            transform-origin: center;
                                            animation: spinner_zKoa 2s linear infinite
                                        }

                                        .spinner_V8m1 circle {
                                            stroke-linecap: round;
                                            animation: spinner_YpZS 1.5s ease-in-out infinite
                                        }

                                        @keyframes spinner_zKoa {
                                            100% {
                                                transform: rotate(360deg)
                                            }
                                        }

                                        @keyframes spinner_YpZS {
                                            0% {
                                                stroke-dasharray: 0 150;
                                                stroke-dashoffset: 0
                                            }

                                            47.5% {
                                                stroke-dasharray: 42 150;
                                                stroke-dashoffset: -16
                                            }

                                            95%,
                                            100% {
                                                stroke-dasharray: 42 150;
                                                stroke-dashoffset: -59
                                            }
                                        }
                                    </style>
                                    <g class="spinner_V8m1">
                                        <circle cx="12" cy="12" r="9.5" fill="none"
                                            stroke-width="3"></circle>
                                    </g>
                                </svg>
                                {{-- Sharing... --}}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    @stack('scripts')
</body>

</html>
