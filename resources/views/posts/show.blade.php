@extends('layouts.app')

@section('content')
    <div class="flex lg:h-[100vh] justify-center items-center ">

        <div
            class="lg:h-[80vh] mx-auto mt-10 bg-background rounded-lg shadow-md lg:shadow-lg flex flex-col lg:flex-row h-fit ">
            <div class="flex justify-between lg:hidden items-center pb-4 border-gray-200">
                <div class="flex items-center">
                    <a href="{{ route('profile.show', $post->user) }}">
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div class="ml-4">
                        <a href="{{ route('profile.show', $post->user) }}"
                            class="text-sm font-bold text-foreground">{{ $post->user->username }}</a>
                    </div>
                    {{-- Waktu Post --}}
                    <div class="ml-2 text-xs text-muted-foreground border-border">
                        <a href="{{ route('post.show', $post) }}">
                            {{ $post->created_at->diffForHumans() }}
                        </a>
                    </div>
                </div>
                <div class="flex ">
                    @if (auth()->check() && auth()->user()->id === $post->user_id)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="ml-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-destructive font-semibold text-sm"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </button>
                        </form>
                    @endif

                    {{-- @if (auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->is_admin)) --}}
                    @if (auth()->check() && auth()->user()->id === $post->user_id)
                        <a href="{{ route('posts.edit', $post) }}" class="text-secondary font-semibold text-sm">Edit</a>
                    @endif
                </div>
            </div>
            {{-- Kolom Gambar --}}
            <div class="relative">
                <img src="{{ $post->image }}" alt="{{ $post->caption }}"
                    class="w-full h-full object-cover rounded-l-lg min-w-full">
                <div class="absolute top-1 left-1 backdrop-blur-2xl bg-white">X</div>
            </div>

            {{-- Kolom Informasi --}}
            <div class="lg:max-w-[500px] p-6 flex flex-col justify-between">
                {{-- Header Post --}}
                <div class="hidden lg:flex items-center pb-4 border-b border-border">
                    <a href="{{ route('profile.show', $post->user) }}">
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div class="ml-4">
                        <a href="{{ route('profile.show', $post->user) }}"
                            class="text-sm font-bold text-foreground">{{ $post->user->username }}</a>
                    </div>
                    {{-- Waktu Post --}}
                    <div class="ml-2 text-xs text-muted-foreground border-border">
                        <a href="{{ route('post.show', $post) }}">
                            {{ $post->created_at->diffForHumans() }}
                        </a>
                    </div>
                    @if (auth()->check() && auth()->user()->id === $post->user_id)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="ml-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-semibold text-sm"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>

             
                {{-- Garis Pemisah --}}
                @if ($post->comments->isNotEmpty() && $post->caption)
                    <hr class="my-4 border-border">
                @endif

                {{-- SEMUA COMMENT Post --}}
                <div class="space-y-4 h-full mt-4 overflow-y-auto">
                    @foreach ($post->comments as $comment)
                        <div class="flex items-start space-x-3">
                            <a href="{{ route('profile.show', $comment->user) }}" class="flex-shrink-0">
                                <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->username }}'s avatar"
                                    class="w-10 h-10 rounded-full object-cover">
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.show', $comment->user) }}"
                                    class="font-bold text-foreground">{{ $comment->user->username }}</a>
                                <span class="text-foreground break-all">{{ $comment->body }}</span>
                                <div class="text-xs text-muted-foreground mt-1">{{ $comment->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>



                {{-- Aksi (Like, Comment) & Caption --}}
                <div class="mt-4">
                    <div class="flex items-center space-x-4">
                        @auth
                            @if (auth()->user()->likes->contains($post))
                                {{-- Jika SUDAH like, tampilkan tombol Un-like (hati merah) --}}
                                <form action="{{ route('post.unlike', $post) }}" method="POST"
                                    class="h-fit flex items-center">
                                    @csrf
                                    <button type="submit">
                                        {{-- <x-bi-heart-fill /> --}}
                                        <x-bx-like />

                                    </button>
                                </form>
                            @else
                                {{-- Jika BELUM like, tampilkan tombol Like (hati outline) --}}

                                <form action="{{ route('post.like', $post) }}" method="POST" class="h-fit flex items-center">
                                    @csrf
                                    <button type="submit">
                                        <x-bx-like />
                                        {{-- <x-bi-heart class="" /> --}}
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
                    <div class="font-bold text-sm mt-2 text-foreground">
                        {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
                    </div>

                    {{-- Caption --}}
                    <div class="text-sm mt-2">
                        <a href="{{ route('profile.show', $post->user) }}"
                            class="font-bold text-foreground">{{ $post->user->username }}</a>
                        <span class="text-muted-foreground">{{ $post->caption }}</span>
                    </div>
                </div>
                {{-- Form Comment dengan Auto-resize Textarea --}}
                <div class="border-t border-border mt-4 pt-4">
                    @auth
                        <form action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="flex w-full space-x-2 items-center justify-center">
                                <textarea name="body" rows="1"
                                    class="w-full border border-border  text-foreground rounded-lg p-2 text-sm  resize-none overflow-hidden"
                                    placeholder="Add a comment..." oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px'"></textarea>
                                @error('body')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <button type="submit"
                                    class=" px-4 py-1 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">Post</button>
                            </div>
                        </form>
                    @endauth
                    @guest
                        <p class="text-sm text-muted-foreground">
                            <a href="{{ route('login') }}" class="text-blue-500 font-semibold">Log in</a> to post a comment.
                        </p>
                    @endguest
                </div>

            </div>
        </div>
    </div>
@endsection
