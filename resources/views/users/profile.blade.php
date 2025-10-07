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
                                <x-ri-verified-badge-fill class="w-7 h-7 text-primary" />
                            </span>
                        @endif
                    </div>
                    @if (Auth::user()->id === $user->id)
                        <a href="{{ route('profile.edit') }}"
                            class="bg-muted-background text-foreground font-semibold py-1 px-3 rounded-md text-sm">
                            Edit Profile
                        </a>
                    @endif
                    @auth
                        {{-- ✅ SESUDAH (CEPAT) --}}
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
                                    class="bg-blue-500 text-white font-semibold py-1 px-3 rounded-md text-sm hover:bg-blue-600">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
                <div class="mt-4 flex space-x-6 sm:space-x-8 text-sm">
                    <div>
                        <span class="font-bold">{{ $user->posts_count }}</span>
                        <span class="text-gray-500 dark:text-[--color-text-tertiary]">posts</span>
                    </div>
                    <div>
                        <span class="font-bold">{{ $user->followers_count }}</span>
                        <span class="text-gray-500 dark:text-[--color-text-tertiary]">followers</span>
                    </div>
                    <div>
                        <span class="font-bold">{{ $user->following_count }}</span>
                        <span class="text-gray-500 dark:text-[--color-text-tertiary]">following</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-bold">{{ $user->name }}</p>
                    <p class="text-muted-foreground mt-2 whitespace-pre-wrap">
                        {{ $user->bio ?? 'This user has no bio yet' }}
                    </p>
                </div>

            </div>
        </div>
        <hr class="my-8 border-border">
        {{-- Galeri Postingan --}}
        <div>
            @if ($user->posts->isNotEmpty())
                <div class="grid grid-cols-3 gap-1 sm:gap-4">
                    @foreach ($user->posts as $post)
                        <a href="{{ route('post.show', $post) }}"> {{-- Nanti ini akan ke halaman detail post --}}
                            <div class="aspect-3/4">
                                <img src="{{ $post->image }}" alt="{{ $post->caption }}"
                                    class="w-full h-full object-cover rounded-md">
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <h2 class="text-2xl font-bold text-gray-800">No Posts Yet</h2>
                    <p class="text-gray-500 mt-2">This user hasn't shared any photos.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
