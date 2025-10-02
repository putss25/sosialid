@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
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
    </div>
@endsection
