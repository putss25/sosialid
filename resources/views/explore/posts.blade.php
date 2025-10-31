@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        {{-- Navigasi Tab --}}
        <div class="mb-8">
            <div class="">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('explore.posts') }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-primary-hover border-primary-hover"
                        aria-current="page">
                        Posts
                    </a>
                    <a href="{{ route('explore.users') }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-muted-foreground hover:text-foreground hover:border-border">
                        People
                    </a>

                </nav>
            </div>
        </div>

        @if ($posts->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-1 sm:gap-4">
                {{-- Card --}}
                @foreach ($posts as $post)
                    {{-- <a href="{{ route('post.show', $post) }}">
                        <div class="aspect-3/4">
                            <img src="{{ $post->image }}" alt="{{ $post->caption }}"
                                class="w-full h-full object-cover rounded-md">
                        </div>
                    </a> --}}
                    <a href="{{ route('post.show', $post) }}" class="relative overflow-hidden group ">
                        <!-- Gambar Card -->
                        <img src="{{ $post->image }}" alt="Post" class="w-full h-full object-cover">

                        <!-- Overlay dengan Gradient -->
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 
                                    transition-opacity duration-200 
                                    flex items-center justify-center gap-6 z-10">

                            {{-- ========================================================== --}}
                            {{-- == UBAHAN: Tambahkan z-20 di sini == --}}
                            {{-- ========================================================== --}}
                            <div class="relative z-20 flex items-center gap-2 text-white font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="white" stroke="white" stroke-width="2">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                                <span>{{ $post->likes_count }}</span>
                            </div>

                            {{-- ========================================================== --}}
                            {{-- == UBAHAN: Tambahkan z-20 di sini == --}}
                            {{-- ========================================================== --}}
                            <div class="relative z-20 flex items-center gap-2 text-white font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="white" stroke="white" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                                <span>{{ $post->comments_count }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <div class="bg-netr   text-center p-10 rounded-lg shadow-md">
                <p class=" text-secondary">You've seen all new posts for now!</p>
            </div>
        @endif
    </div>
@endsection
