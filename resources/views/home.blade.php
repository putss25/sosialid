@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto ">
        @if ($posts->count() > 0)
            @foreach ($posts as $post)
                <div class="bg-background rounded-lg  mb-8">
                    {{-- Header Post --}}
                    <div class="flex items-center p-4 border-b">
                        <a href="{{ route('profile.show', $post->user) }}">
                            <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                                class="w-10 h-10 rounded-full object-cover">
                        </a>
                        <div class="ml-4">
                            <a href="{{ route('profile.show', $post->user) }}"
                                class="text-sm font-bold">{{ $post->user->username }}</a>
                        </div>
                        <div class="text-xs text-gray-400  ml-2">
                            <a href="{{ route('post.show', $post) }}">
                                - {{ $post->created_at->diffForHumans() }}
                            </a>
                        </div>
                    </div>

                    {{-- Gambar Post --}}
                    <div>
                        <a href="{{ route('post.show', $post) }}">
                            <img src="{{ $post->image }}" alt="{{ $post->caption }}" class="w-full">
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="p-4">
                            <div class="flex items-center space-x-4">
                                @auth
                                    @if (auth()->user()->likes->contains($post))
                                        {{-- Jika SUDAH like, tampilkan tombol Un-like (hati merah) --}}
                                        <form action="{{ route('post.unlike', $post) }}" method="POST"
                                            class="h-fit flex items-center">
                                            @csrf
                                            <button type="submit">
                                                <svg class="w-6 h-6 text-red-500 fill-current"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Jika BELUM like, tampilkan tombol Like (hati outline) --}}

                                        <form action="{{ route('post.like', $post) }}" method="POST"
                                            class="h-fit flex items-center">
                                            @csrf
                                            <button type="submit">
                                                <svg class="w-6 h-6 text-foreground hover:text-red-500"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 016.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth

                                {{-- Tombol Comment (Placeholder) --}}
                                <a href="{{ route('post.show', $post) }}">
                                    <x-far-comment class="w-6 h-6 text-foreground" />

                                </a>
                            </div>

                            {{-- Jumlah Like --}}
                            <div class="font-bold text-sm mt-2">
                                {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
                            </div>

                            {{-- Caption --}}
                            @isset($post->caption)
                                <div class="text-sm mt-2">
                                    <a href="{{ route('profile.show', $post->user) }}"
                                        class="font-bold">{{ $post->user->username }}</a>
                                    <span class="text-foreground">{{ $post->caption }}</span>
                                </div>
                            @endisset

                        </div>
                    </div>
            @endforeach

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center p-10 bg-background rounded-lg shadow-md">
                <h2 class="text-2xl font-bold">Welcome to your Feed!</h2>
                <p class="text-gray-500 mt-2">It's quiet here. Start following people to see their posts.</p>
            </div>
        @endif
    </div>
@endsection
