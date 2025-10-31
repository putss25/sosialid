<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Soasialid') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
    <link rel='icon' href="{{ asset('images/LogoSnapishort.svg')}}" type="image/svg+xml" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Responsive improvements */
        @media (max-width: 1023px) {
            .main-content {
                padding-bottom: 80px;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Better image cropper container */
        .cropper-container {
            max-height: 60vh;
        }

        /* Modal responsive sizing */
        @media (max-width: 640px) {
            .modal-content {
                width: 95vw !important;
                max-height: 85vh;
            }
        }

        /* Navigation active state */
        .nav-active {
            background-color: hsl(var(--muted-background));
            color: hsl(var(--foreground));
        }
    </style>
    <script>
        // Theme functions
        function applyTheme(theme) {
            if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        function setTheme(newTheme) {
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
            updateThemeButtons(newTheme);
        }

        function updateThemeButtons(currentTheme) {
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

        const savedTheme = localStorage.getItem('theme') || 'system';
        applyTheme(savedTheme);

        document.addEventListener('DOMContentLoaded', function() {
            updateThemeButtons(savedTheme);
        });

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
                        responsive: true,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
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
                const croppedImageBase64 = croppedCanvas.toDataURL('image/jpeg', 0.9);
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
    }" class="flex min-h-screen bg-background">
        <x-notification />

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex flex-shrink-0 w-52 xl:w-64 flex-col border-r border-border fixed h-screen z-10">
            <div class="py-6 xl:py-8 flex items-center justify-between flex-shrink-0 px-4 xl:px-6">
                <img src="/images/snapi.svg" class="w-[40%] min-w-[80px]" alt="Snapi Logo">
                
                <!-- Messages Icon for Desktop (Top Right) -->
                <a href="{{ route('chat.index') }}" class="relative p-2 hover:opacity-70 transition lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-message-circle text-foreground">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
                    </svg>
                    <!-- Notification Badge (optional) -->
                    <!-- <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span> -->
                </a>
            </div>

            <nav class="flex-grow flex flex-col text-muted-foreground text-lg lg:text-xl gap-1">
                <!-- Home Link -->
                <a href="{{ route('home') }}"
                    class="flex items-center px-3 xl:px-4 py-2.5 xl:py-3 hover:text-foreground hover:bg-muted-background font-semibold transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('home') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-house text-foreground mr-3 flex-shrink-0">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="hidden lg:inline">Home</span>
                </a>
                
                <!-- Create Button -->
                <button @click="createModalOpen = true"
                    class="flex items-center px-3 xl:px-4 py-2.5 xl:py-3 hover:text-foreground hover:bg-muted-background font-semibold transition-all duration-200 rounded-lg mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-plus text-foreground mr-3 flex-shrink-0">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    <span class="hidden lg:inline">Create</span>
                </button>
                
                <!-- Search Link -->
                <a href="{{ route('search.index') }}"
                    class="flex items-center px-3 xl:px-4 py-2.5 xl:py-3 hover:text-foreground hover:bg-muted-background font-semibold transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('search.index') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-search text-foreground mr-3 flex-shrink-0">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <span class="hidden lg:inline">Search</span>
                </a>
                
                <!-- Explore Link -->
                <a href="{{ route('explore.posts') }}"
                    class="flex items-center px-3 xl:px-4 py-2.5 xl:py-3 hover:text-foreground hover:bg-muted-background font-semibold transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('explore.posts') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-compass text-foreground mr-3 flex-shrink-0">
                        <path d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    <span class="hidden lg:inline">Explore</span>
                </a>

                <!-- Messages Link -->
                <a href="{{ route('chat.index') }}"
                    class="flex items-center px-3 xl:px-4 py-2.5 xl:py-3 hover:text-foreground hover:bg-muted-background font-semibold transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('chat.index') ? 'bg-muted-background text-foreground' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-message-circle text-foreground mr-3 flex-shrink-0">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
                    </svg>
                    <span class="hidden lg:inline">Messages</span>
                </a>

                <!-- Profile Link -->
                <a href="{{ route('profile.show', auth()->user()) }}"
                    class="flex items-center px-3 xl:px-4 py-2.5 xl:py-3 hover:text-foreground hover:bg-muted-background font-semibold transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('profile.show', auth()->user()) ? 'bg-muted-background text-foreground' : '' }}">
                    <img class="w-6 h-6 mr-3 object-cover rounded-full flex-shrink-0" src="{{ Auth::user()->avatar }}"
                        alt="Your avatar">
                    <span class="hidden lg:inline truncate">Profile</span>
                </a>
            </nav>

            <!-- Dropdown Menu -->
            <div class="mt-auto px-4 xl:px-6 py-6 xl:py-8">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block overflow-hidden focus:outline-none hover:opacity-70 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-menu text-foreground">
                            <path d="M4 5h16" />
                            <path d="M4 12h16" />
                            <path d="M4 19h16" />
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute bottom-full left-0 mb-2 w-48 bg-muted-background rounded-lg shadow-xl z-20 text-base overflow-hidden"
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">
                        <a href="{{ route('settings.index') }}"
                            class="block px-4 py-3 text-muted-foreground hover:bg-primary hover:text-white transition">
                            Settings
                        </a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-3 text-muted-foreground hover:bg-primary hover:text-white transition">
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-4 py-3 text-muted-foreground hover:bg-primary hover:text-white transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-52 xl:ml-64">
            <!-- Mobile Header -->
            <header class="lg:hidden sticky top-0 flex justify-between items-center p-3 px-5 bg-background border-b z-30">
                <img src="/images/snapi.svg" class="w-[15%] max-w-[100px] min-w-[60px]" alt="Snapi Logo">
                
                <!-- Header Right Actions -->
                <div class="flex items-center gap-3">
                    <!-- Messages Icon -->
                    <a href="{{ route('chat.index') }}" class="relative p-2 hover:opacity-70 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-message-circle text-foreground">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
                        </svg>
                        <!-- Notification Badge (optional) -->
                        <!-- <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span> -->
                    </a>

                    <!-- Menu Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden focus:outline-none p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-menu text-foreground">
                                <path d="M4 5h16" />
                                <path d="M4 12h16" />
                                <path d="M4 19h16" />
                            </svg>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                            class="absolute top-full right-0 mt-2 w-48 bg-muted-background rounded-lg shadow-xl z-20 text-base overflow-hidden"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95">
                            <a href="{{ route('settings.index') }}"
                                class="block px-4 py-3 text-muted-foreground hover:bg-primary hover:text-white transition">
                                Settings
                            </a>
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-3 text-muted-foreground hover:bg-primary hover:text-white transition">
                                    Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-3 text-muted-foreground hover:bg-primary hover:text-white transition">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto pb-20 lg:pb-6 main-content">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="lg:hidden fixed bottom-0 left-0 w-full bg-background border-t flex justify-around items-center z-30 safe-area-bottom">
            <!-- Home -->
            <a href="{{ route('home') }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 py-2 text-xs font-semibold transition {{ request()->routeIs('home') ? 'text-primary' : 'text-foreground' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-house mb-1">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span class="text-[10px] sm:text-xs">Home</span>
            </a>

            <!-- Search -->
            <a href="{{ route('search.index') }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 py-2 text-xs font-semibold transition {{ request()->routeIs('search.index') ? 'text-primary' : 'text-foreground' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-search mb-1">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
                <span class="text-[10px] sm:text-xs">Search</span>
            </a>

            <!-- Create -->
            <button @click="createModalOpen = true"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 py-2 text-xs font-semibold text-foreground transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-plus mb-1">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                <span class="text-[10px] sm:text-xs">Create</span>
            </button>

            <!-- Explore -->
            <a href="{{ route('explore.posts') }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 py-2 text-xs font-semibold transition {{ request()->routeIs('explore.posts') ? 'text-primary' : 'text-foreground' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-compass mb-1">
                    <path d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <span class="text-[10px] sm:text-xs">Explore</span>
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.show', auth()->user()) }}"
                class="flex flex-col flex-1 items-center justify-center text-center p-3 py-2 text-xs font-semibold transition {{ request()->routeIs('profile.show', auth()->user()) ? 'text-primary' : 'text-foreground' }}">
                <img class="w-6 h-6 mb-1 object-cover rounded-full {{ request()->routeIs('profile.show', auth()->user()) ? 'ring-2 ring-primary' : '' }}" 
                    src="{{ Auth::user()->avatar }}" alt="Your avatar">
                <span class="text-[10px] sm:text-xs">Profile</span>
            </a>
        </nav>

        <!-- Create Post Modal -->
        <div x-show="createModalOpen" @keydown.escape.window="closeModal()" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="closeModal()"
                class="bg-background rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto modal-content"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">
                
                <!-- Modal Header -->
                <div class="px-4 py-3 border-b flex justify-between items-center sticky top-0 bg-background z-10 rounded-t-xl">
                    <h3 class="font-semibold text-base sm:text-lg"
                        x-text="step === 3 ? 'Write a caption' : (step === 2 ? 'Crop Image' : 'Create new post')"></h3>
                    <button x-show="step === 2" @click="cropAndProceed()"
                        class="text-sm font-bold text-primary hover:text-primary-hover transition">Next</button>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-800 text-2xl leading-none transition"
                        x-show="step !== 2">&times;</button>
                </div>

                <!-- Modal Form -->
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                    @submit="isSubmitting = true">
                    @csrf
                    <input type="hidden" name="cropped_image" id="cropped-image-data">
                    
                    <div class="p-4 sm:p-6">
                        <!-- Step 1: Select Image -->
                        <div x-show="step === 1" class="flex flex-col items-center justify-center text-center py-10 sm:py-16">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-foreground mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-lg sm:text-xl mb-2">Select an image to start</p>
                            <p class="text-xs sm:text-sm text-muted-foreground mb-4 px-4">Drag photos here or click to browse</p>
                            <label for="image-upload"
                                class="px-4 sm:px-6 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600 transition text-sm sm:text-base">
                                Select from computer
                            </label>
                            <input id="image-upload" name="image" type="file" class="hidden"
                                @change="handleFileSelect($event)" accept="image/*">
                        </div>

                        <!-- Step 2: Crop Image -->
                        <div x-show="step === 2" class="w-full">
                            <div class="w-full overflow-hidden rounded-lg">
                                <img id="image-to-crop" :src="imageUrl" class="block max-w-full" style="max-height: 60vh;">
                            </div>
                        </div>

                        <!-- Step 3: Add Caption -->
                        <div x-show="step === 3" class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2">
                                <img :src="imageUrl" alt="Image preview"
                                    class="rounded-lg object-cover w-full aspect-[3/4]">
                            </div>
                            <div class="w-full sm:w-1/2">
                                <textarea name="caption" rows="8"
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    placeholder="Write a caption..."></textarea>
                                @error('caption')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div x-show="step === 3" class="px-4 py-3 border-t flex justify-end gap-2 sticky bottom-0 bg-background rounded-b-xl">
                        <button type="button" @click="closeModal()"
                            class="px-4 sm:px-6 py-2 bg-muted-background text-muted-foreground rounded-lg hover:opacity-80 transition text-sm sm:text-base">
                            Cancel
                        </button>
                        <button type="submit" :disabled="isSubmitting"
                            class="px-4 sm:px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[80px] text-sm sm:text-base">
                            <!-- Loading State -->
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg width="20" height="20" class="stroke-white animate-spin" viewBox="0 0 24 24"
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
                                            95%, 100% {
                                                stroke-dasharray: 42 150;
                                                stroke-dashoffset: -59
                                            }
                                        }
                                    </style>
                                    <g class="spinner_V8m1">
                                        <circle cx="12" cy="12" r="9.5" fill="none" stroke-width="3"></circle>
                                    </g>
                                </svg>
                            </span>
                            <!-- Default State -->
                            <span x-show="!isSubmitting">Share</span>
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