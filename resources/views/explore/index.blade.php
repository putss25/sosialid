@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        {{-- Navigasi Tab --}}
        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-[--color-border]">
                <nav class="mb-8 flex space-x-8" aria-label="Tabs">
                      <a href="{{ route('explore.posts') }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-muted-foreground hover:text-foreground hover:border-border">
                        Posts
                    </a>
                    <a href="{{ route('explore.index') }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-primary border-primary"
                        aria-current="page">
                        People
                    </a>

                </nav>
            </div>

            @if ($users->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($users as $user)
                        <div
                            class="bg-white dark:bg-[--color-surface] border border-[--color-border] rounded-lg p-4 flex flex-col items-center text-center">
                            <a href="{{ route('profile.show', $user) }}">
                                <img src="{{ $user->avatar }}" alt="{{ $user->username }}'s avatar"
                                    class="w-20 h-20 rounded-full object-cover mb-4">
                            </a>
                            <a href="{{ route('profile.show', $user) }}"
                                class="font-bold text-[--color-text] truncate w-full">{{ $user->username }}</a>
                            <p class="text-sm text-[--color-text-secondary] truncate w-full mb-4">{{ $user->name }}</p>

                            {{-- Kita gunakan kembali logika tombol Follow yang sudah ada --}}
                            <form action="{{ route('profile.follow', $user) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-500 text-white font-semibold py-2 px-3 rounded-md text-sm hover:bg-blue-600">
                                    Follow
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-[--color-surface] text-center p-10 rounded-lg shadow-md">
                    <p class="text-[--color-text-secondary]">There's no one new to discover right now.</p>
                </div>
            @endif
        </div>
    @endsection
