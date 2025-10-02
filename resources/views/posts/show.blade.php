@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 bg-white rounded-lg shadow-md flex flex-col md:flex-row">
        {{-- Kolom Gambar --}}
        <div class="md:w-1/2">
            <img src="{{ $post->image }}" alt="{{ $post->caption }}" class="w-full h-full object-cover rounded-l-lg">
        </div>

        {{-- Kolom Informasi --}}
        <div class="md:w-1/2 p-6 flex flex-col justify-between">
            {{-- Header Post --}}
            <div class="flex items-center pb-4 border-b border-gray-200">
                <a href="{{ route('profile.show', $post->user) }}">
                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                        class="w-10 h-10 rounded-full object-cover">
                </a>
                <div class="ml-4">
                    <a href="{{ route('profile.show', $post->user) }}"
                        class="text-sm font-bold">{{ $post->user->username }}</a>
                </div>
                @if (auth()->check() && auth()->user()->id === $post->user_id)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 font-semibold text-sm"
                            onclick="return confirm('Are you sure you want to delete this post?')">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
            @if ($post->caption)
                <div class="flex items-start space-x-3 mb-4">
                    <a href="{{ route('profile.show', $post->user) }}">
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div>
                        <a href="{{ route('profile.show', $post->user) }}"
                            class="font-bold">{{ $post->user->username }}</a>
                        <span>{{ $post->caption }}</span>
                        <div class="text-xs text-gray-400 mt-1">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endif

            {{-- Garis Pemisah --}}
            @if ($post->comments->isNotEmpty() && $post->caption)
                <hr class="my-4">
            @endif
            <div class="max-h-40 overflow-auto">
                @foreach ($post->comments as $comment)
                    <div class="flex items-start space-x-3 mb-4">
                        <a href="{{ route('profile.show', $comment->user) }}">
                            <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->username }}'s avatar"
                                class="w-10 h-10 rounded-full object-cover">
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $comment->user) }}"
                                class="font-bold">{{ $comment->user->username }}</a>
                            <span>{{ $comment->body }}</span>
                            <div class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-200 mt-4 pt-4">
                @auth
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="flex items-start space-x-4">
                            <img src="{{ auth()->user()->avatar }}" alt="Your avatar"
                                class="w-10 h-10 rounded-full object-cover">
                            <div class="flex-1">
                                <textarea name="body" rows="1"
                                    class="w-full border rounded-lg p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Add a comment..."></textarea>
                                @error('body')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <button type="submit"
                                    class="mt-2 px-4 py-1 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">Post</button>
                            </div>
                        </div>
                    </form>
                @endauth
                @guest
                    <p class="text-sm text-gray-500">
                        <a href="{{ route('login') }}" class="text-blue-500 font-semibold">Log in</a> to post a comment.
                    </p>
                @endguest
            </div>

            {{-- Aksi (Like, Comment) & Caption --}}
            <div class="">
                <div class="flex items-center space-x-4">
                    @auth
                        @if (auth()->user()->likes->contains($post))
                            {{-- Jika SUDAH like, tampilkan tombol Un-like (hati merah) --}}
                            <form action="{{ route('post.unlike', $post) }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <svg class="w-6 h-6 text-red-500 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                    </svg>
                                </button>
                            </form>
                        @else
                            {{-- Jika BELUM like, tampilkan tombol Like (hati outline) --}}
                            <form action="{{ route('post.like', $post) }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <svg class="w-6 h-6 text-gray-500 hover:text-red-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 016.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @endauth

                    {{-- Tombol Comment (Placeholder) --}}
                    <a href="{{ route('post.show', $post) }}">
                        <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 5.523-4.477 10-10 10S1 17.523 1 12 5.477 2 11 2s10 4.477 10 10z" />
                        </svg>
                    </a>
                </div>

                {{-- Jumlah Like --}}
                <div class="font-bold text-sm mt-2">
                    {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
                </div>

                {{-- Caption --}}
                <div class="text-sm mt-2">
                    <a href="{{ route('profile.show', $post->user) }}" class="font-bold">{{ $post->user->username }}</a>
                    <span class="text-gray-700">{{ $post->caption }}</span>
                </div>

                {{-- Waktu Post --}}
                <div class="text-xs text-gray-400 mt-4 border-t border-gray-200 pt-4">
                    <a href="{{ route('post.show', $post) }}">
                        {{ $post->created_at->diffForHumans() }}
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
