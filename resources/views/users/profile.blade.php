@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
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
                    <h1 class="text-2xl font-bold text-gray-800">{{ $user->username }}</h1>
                    @if (Auth::user()->id === $user->id)
                        <a href="{{ route('profile.edit') }}"
                            class="bg-gray-200 text-gray-800 font-semibold py-1 px-3 rounded-md text-sm">
                            Edit Profile
                        </a>
                    @endif
                    @auth
                        @if (auth()->user()->is($user))
                            {{-- Jika ini adalah profil kita sendiri, tampilkan tombol Edit Profile --}}
                            <a href="{{ route('profile.edit') }}"
                                class="bg-gray-200 text-gray-800 font-semibold py-1 px-3 rounded-md text-sm">
                                Edit Profile
                            </a>
                        @else
                            {{-- Jika ini profil orang lain, tampilkan tombol Follow/Unfollow --}}
                            @if (auth()->user()->following->contains($user))
                                {{-- Jika SUDAH follow, tampilkan tombol Unfollow --}}
                                <form action="{{ route('profile.unfollow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-gray-200 text-gray-800 font-semibold py-1 px-3 rounded-md text-sm">
                                        Unfollow
                                    </button>
                                </form>
                            @else
                                {{-- Jika BELUM follow, tampilkan tombol Follow --}}
                                <form action="{{ route('profile.follow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-blue-500 text-white font-semibold py-1 px-3 rounded-md text-sm hover:bg-blue-600">
                                        Follow
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
                <div class="flex space-x-6 mt-4">
                    <div><span class="font-bold">0</span> posts</div>
                    <div><span class="font-bold">0</span> followers</div>
                    <div><span class="font-bold">0</span> following</div>
                </div>
                <div class="mt-4">
                    <p class="font-bold">{{ $user->name }}</p>
                    <p class="text-gray-600 mt-2 whitespace-pre-wrap">{{ $user->bio ?? 'This user has no bio yet' }}</p>
                </div>

            </div>
        </div>
        <hr class="my-8 border-gray-300">
        {{-- Galeri Postingan --}}
        <div>
            @if ($user->posts->isNotEmpty())
                <div class="grid grid-cols-3 gap-1 sm:gap-4">
                    @foreach ($user->posts as $post)
                        <a href="{{ route('post.show', $post) }}"> {{-- Nanti ini akan ke halaman detail post --}}
                            <div class="aspect-square">
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
