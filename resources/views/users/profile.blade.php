@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4" x-data="{
        modalOpen: false,
        modalTitle: '',
        users: []
    }" @keydown.escape.window="modalOpen = false">

        @if (session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        {{-- Header Profile --}}
        <div class="mb-11">
            <div class="flex items-start gap-6 md:gap-20">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    <img src="{{ $user->avatar }}" alt="{{ $user->username }}'s avatar"
                        class="w-20 h-20 md:w-36 md:h-36 rounded-full object-cover border">
                </div>

                {{-- Info Profile --}}
                <div class="flex-1">
                    {{-- Username & Actions (Desktop: side by side, Mobile: username only) --}}
                    <div class="mb-5">
                        <div class="flex flex-col md:flex-row md:items-center md:gap-5">
                            {{-- Username with verified badge --}}
                            <div class="flex items-center gap-2 mb-3 md:mb-0">
                                <h1 class="text-xl font-normal">{{ $user->username }}</h1>

                                @if ($user->is_verified)
                                    <span title="Verified account">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            <div class="flex md:hidden justify-start gap-4 py-3  mb-5">
                                <div class="text-center">
                                    <div class="font-semibold">{{ $user->posts_count }}</div>
                                    <div class="text-gray-600 text-sm">posts</div>
                                </div>
                                <button
                                    @click="modalOpen = true; modalTitle = 'Followers'; users = {{ $user->followers->toJson() }}"
                                    class="text-center hover:text-gray-500">
                                    <div class="font-semibold">{{ $user->followers_count }}</div>
                                    <div class="text-gray-600 text-sm">followers</div>
                                </button>
                                <button
                                    @click="modalOpen = true; modalTitle = 'Following'; users = {{ $user->following->toJson() }}"
                                    class="text-center hover:text-gray-500">
                                    <div class="font-semibold">{{ $user->following_count }}</div>
                                    <div class="text-gray-600 text-sm">following</div>
                                </button>
                            </div>

                            {{-- Action Buttons (Desktop only) --}}
                            @auth
                                <div class="hidden md:flex md:items-center md:gap-2">
                                    @if (Auth::user()->id === $user->id)
                                        {{-- Edit Profile Button --}}
                                        <a href="{{ route('settings.index') }}"
                                            class="bg-gray-200 hover:bg-gray-300 text-black font-semibold py-1.5 px-4 rounded-lg text-sm">
                                            Edit profile
                                        </a>
                                    @else
                                        {{-- Follow/Unfollow & Message Buttons --}}
                                        @if ($isFollowing)
                                            <form action="{{ route('profile.unfollow', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-gray-200 hover:bg-gray-300 text-black font-semibold py-1.5 px-6 rounded-lg text-sm">
                                                    Following
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('profile.follow', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1.5 px-6 rounded-lg text-sm">
                                                    Follow
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('chat.show', $user) }}"
                                            class="bg-gray-200 hover:bg-gray-300 text-black font-semibold py-1.5 px-6 rounded-lg text-sm">
                                            Message
                                        </a>
                                    @endif
                                </div>
                            @endauth
                        </div>
                    </div>

                    {{-- Stats (Desktop only, di bawah username) --}}
                    <div class="hidden md:flex gap-10 mb-5">
                        <div>
                            <span class="font-semibold">{{ $user->posts_count }}</span>
                            <span class="text-gray-600">posts</span>
                        </div>
                        <button
                            @click="modalOpen = true; modalTitle = 'Followers'; users = {{ $user->followers->toJson() }}"
                            class="text-left hover:text-gray-500">
                            <span class="font-semibold">{{ $user->followers_count }}</span>
                            <span class="text-gray-600">followers</span>
                        </button>
                        <button
                            @click="modalOpen = true; modalTitle = 'Following'; users = {{ $user->following->toJson() }}"
                            class="text-left hover:text-gray-500">
                            <span class="font-semibold">{{ $user->following_count }}</span>
                            <span class="text-gray-600">following</span>
                        </button>
                    </div>

                    {{-- Name & Bio (Desktop only) --}}
                    <div class="hidden md:block">
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-sm whitespace-pre-line break-words">
                            {{ $user->bio ?? 'This user has no bio yet' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mobile: Stats, Name, Bio & Buttons (di bawah foto profile) --}}
            <div class="md:hidden mt-5">
                {{-- Stats (Mobile) - PINDAH KE ATAS --}}


                {{-- Name & Bio --}}
                <div class="mb-5">
                    <p class="font-semibold">{{ $user->name }}</p>
                    <p class="text-sm whitespace-pre-line break-words">
                        {{ $user->bio ?? 'This user has no bio yet' }}
                    </p>
                </div>

                {{-- Action Buttons (Mobile) --}}
                @auth
                    <div class="mb-5">
                        @if (Auth::user()->id === $user->id)
                            <a href="{{ route('settings.index') }}"
                                class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-black font-semibold py-1.5 px-4 rounded-lg text-sm">
                                Edit profile
                            </a>
                        @else
                            <div class="flex gap-2">
                                @if ($isFollowing)
                                    <form action="{{ route('profile.unfollow', $user) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-gray-200 hover:bg-gray-300 text-black font-semibold py-1.5 px-6 rounded-lg text-sm">
                                            Following
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('profile.follow', $user) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1.5 px-6 rounded-lg text-sm">
                                            Follow
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('chat.show', $user) }}"
                                    class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-black font-semibold py-1.5 px-6 rounded-lg text-sm">
                                    Message
                                </a>
                            </div>
                        @endif
                    </div>
                @endauth
            </div>
        </div>

        {{-- Posts Grid --}}
        <div class="mt-1 border-t pt-4">
            @if ($posts->isNotEmpty())
                <div class="grid grid-cols-3 gap-1">
                    @foreach ($posts as $post)
                        <a href="{{ route('post.show', $post) }}"
                            class="group relative block aspect-square overflow-hidden">
                            <img src="{{ $post->image }}" alt="{{ $post->caption }}" class="w-full h-full object-cover">

                            {{-- Hover Overlay --}}
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 
                                    transition-opacity duration-200 
                                    flex items-center justify-center gap-6 z-10">

                                <div class="relative z-20 flex items-center gap-2 text-white font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>
                                    <span>{{ $post->likes_count }}</span>
                                </div>

                                <div class="relative z-20 flex items-center gap-2 text-white font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                    <span>{{ $post->comments_count }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full border-2 border-black mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-light mb-2">No Posts Yet</h2>
                </div>
            @endif
        </div>

        {{-- Modal Follower/Following --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalOpen = false"
                class="bg-white rounded-lg shadow-xl w-11/12 md:w-96 max-h-[70vh] flex flex-col"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                {{-- Header Modal --}}
                <div class="px-4 py-3 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-lg" x-text="modalTitle"></h3>
                    <button @click="modalOpen = false"
                        class="text-gray-500 hover:text-gray-800 text-2xl leading-none">&times;</button>
                </div>

                {{-- Daftar User (Body Modal) --}}
                <div class="p-4 overflow-y-auto">
                    <template x-if="users.length === 0">
                        <p class="text-gray-500 text-center py-4">No users to show.</p>
                    </template>

                    <template x-for="user in users" :key="user.id">
                        <div class="flex items-center justify-between py-2">
                            <a :href="`/${user.username}`" class="flex items-center gap-3">
                                <img :src="user.avatar" :alt="user.username"
                                    class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-semibold text-sm" x-text="user.username"></p>
                                    <p class="text-xs text-gray-500" x-text="user.name"></p>
                                </div>
                            </a>

                            <div x-show="user.id !== {{ auth()->id() }}">
                                <a :href="`/${user.username}`"
                                    class="bg-blue-500 text-white font-semibold py-1 px-3 rounded-md text-xs">
                                    View
                                </a>
                            </div>
                            <div x-show="user.id === {{ auth()->id() }}">
                                <span class="text-sm text-gray-400">You</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
@endsection
