@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        {{-- Navigasi Tab --}}
        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-[--color-border]">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('explore.posts') }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-primary border-primary"
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
                            class="absolute inset-0 bg-gradient-to-b from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="p-5 text-white">
                                <p class="font-semibold text-lg">{{ $post->user->username }}</p>
                                <div class="flex flex-col   text-sm mt-2">
                                    <span>
                                        {{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}
                                    </span>
                                    <span>
                                        {{ $post->comments_count }} {{ Str::plural('Comment', $post->comments_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-[--color-surface] text-center p-10 rounded-lg shadow-md">
                <p class="text-[--color-text-secondary]">You've seen all new posts for now!</p>
            </div>
        @endif
    </div>
@endsection
