@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Search Users</h1>

        {{-- Form Pencarian --}}
        <form action="{{ route('search.index') }}" method="GET" class="mb-8">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or username..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <div class="absolute top-0 left-0 inline-flex items-center p-2">
                    <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </form>

        {{-- Hasil Pencarian --}}
        <div class="bg-muted-background p-6 rounded-lg shadow-md">
            @if (request('q'))
                <h2 class="text-xl font-semibold mb-4">Search results for "{{ request('q') }}"</h2>
                <div class="space-y-4">
                    @forelse ($users as $user)
                        <div class="flex items-center justify-between">
                            <a href="{{ route('profile.show', $user) }}" class="flex items-center space-x-4">
                                <img src="{{ $user->avatar }}" alt="{{ $user->username }}"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <p class="font-bold ]">{{ $user->username }}</p>
                                    <p class="text-sm  text-secondary]">{{ $user->name }}</p>
                                </div>
                            </a>
                            {{-- Tombol Follow/Unfollow bisa ditambahkan di sini jika mau --}}
                        </div>
                    @empty
                        <p class=" text-secondary]">No users found.</p>
                    @endforelse
                </div>
            @else
                <p class=" text-secondary]">Enter a name or username to search for users.</p>
            @endif
        </div>

    </div>
@endsection
