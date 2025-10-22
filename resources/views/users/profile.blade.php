@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-6">
        @if (session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        <div class="flex items-center p-4">
            <div class="w-1/4">
                <img src="{{ $user->avatar }}" alt="{{ $user->username }}'s avatar"
                    class="w-32 h-32 bg-gray-300 rounded-full object-cover">
            </div>
            <div class="w-3/4 ml-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2"> {{-- Ubah div sebelumnya agar ada space-x-2 --}}
                        <h1 class="text-2xl font-bold text-foreground">{{ $user->username }}</h1>

                        @if ($user->is_verified)
                            <span title="Verified account" class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-check-icon lucide-check bg-primary rounded-full text-white p-1">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>

                            </span>
                        @endif
                    </div>

                    @auth
                      @if (Auth::user()->id !== $user->id)
                            @if ($isFollowing)
                                {{-- Unfollow button --}}
                                <form action="{{ route('profile.unfollow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-gray-200 text-gray-800 font-semibold py-1 px-3 rounded-md text-sm">
                                        Unfollow
                                    </button>
                                </form>
                            @else
                                {{-- Follow button --}}
                                <form action="{{ route('profile.follow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-primary text-white font-semibold py-1 px-3 rounded-md text-sm hover:bg-primary-hover">
                                        Follow
                                    </button>
                                </form>
                            @endif
                        @endif

                    @endauth
                </div>
                <div class="mt-4 flex space-x-6 sm:space-x-8 text-sm">
                    <div>
                        <span class="font-bold">{{ $user->posts_count }}</span>
                        <span class="text-gray-500 ">posts</span>
                    </div>
                    <div>
                        <span class="font-bold">{{ $user->followers_count }}</span>
                        <span class="text-gray-500 ">followers</span>
                    </div>
                    <div>
                        <span class="font-bold">{{ $user->following_count }}</span>
                        <span class="text-gray-500 ">following</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-bold">{{ $user->name }}</p>
                    <p class="text-muted-foreground mt-2 break-words">
                        {{ $user->bio ?? 'This user has no bio yet' }}
                    </p>
                </div>

                <div class="mt-4">
                    @if (Auth::user()->id === $user->id)
                        <a href="{{ route('settings.index') }}"
                            class="bg-muted-background text-foreground font-semibold py-1 px-3 rounded-md text-md">
                            Edit Profile
                        </a>
                    @endif
                </div>

            </div>
        </div>
        <hr class="my-8 border-border">
        {{-- Galeri Postingan --}}
        <div>
            @if ($posts->isNotEmpty())
                <div class="grid grid-cols-3 gap-1 sm:gap-4">
                    @foreach ($posts as $post)
                        {{-- Card --}}
                        <a href="{{ route('post.show', $post) }}" class="group relative overflow-hidden">
                            {{-- Nanti ini akan ke halaman detail post --}}
                            <div class="aspect-3/4">
                                <img src="{{ $post->image }}" alt="{{ $post->caption }}"
                                    class="w-full h-full object-cover  ">

                                <div
                                    class="absolute inset-0 bg-gradient-to-b from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="p-5 text-white">
                                        {{-- <p class="font-semibold text-lg">{{ $post->user->username }}</p> --}}
                                        <div class="flex flex-col   text-sm mt-2">
                                            <span>
                                                {{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}
                                            </span>
                                            <span>
                                                {{ $post->comments_count }}
                                                {{ Str::plural('Comment', $post->comments_count) }}
                                            </span>
                                        </div>
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
                <div class="text-center py-10">
                    <h2 class="text-2xl font-bold text-foreground">No Posts Yet</h2>
                    <p class="text-muted-foreground mt-2">This user hasn't shared any photos.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
