@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto ">
        @if ($posts->count() > 0)
            @foreach ($posts as $post)
                {{-- CARD --}}
                {{-- ========================================================== --}}
                {{-- == UBAHAN 1: Tambahkan x-data di div card utama == --}}
                {{-- ========================================================== --}}
                <div class="bg-background rounded-lg space-y-4 py-4 mb-8"
                     x-data="{
                        isLiked: {{ auth()->user()->likes->contains($post) ? 'true' : 'false' }},
                        likeCount: {{ $post->likes->count() }},
                        isSubmitting: false
                     }">

                    {{-- Header Post (Tidak berubah) --}}
                    <div class="flex items-center px-3 lg:px-0">
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

                    {{-- Gambar Post (Tidak berubah) --}}
                    <div>
                        <a href="{{ route('post.show', $post) }}">
                            <img src="{{ $post->image }}" alt="{{ $post->caption }}" class="w-full">
                        </a>
                    </div>

                    <div class="mt-4 px-3 lg:px-0">
                        <div class="flex items-center space-x-3">
                            @auth
                                {{-- ========================================================== --}}
                                {{-- == UBAHAN 2: Ganti <form> dengan <button> Alpine.js == --}}
                                {{-- ========================================================== --}}
                                
                                {{-- Tombol UN-LIKE (Hati Merah) - Muncul jika isLiked = true --}}
                                <button type="button" x-show="isLiked"
                                        @click="
                                            isSubmitting = true;
                                            axios.post('{{ route('post.unlike', $post) }}')
                                                .then(response => {
                                                    isLiked = false;
                                                    likeCount = response.data.likeCount;
                                                })
                                                .finally(() => isSubmitting = false);
                                        "
                                        :disabled="isSubmitting"
                                        class="disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="red" stroke="red" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="fill-accent stroke-accent">
                                        <path
                                            d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                </button>

                                {{-- Tombol LIKE (Hati Outline) - Muncul jika isLiked = false --}}
                                <button type="button" x-show="!isLiked"
                                        @click="
                                            isSubmitting = true;
                                            axios.post('{{ route('post.like', $post) }}')
                                                .then(response => {
                                                    isLiked = true;
                                                    likeCount = response.data.likeCount;
                                                })
                                                .finally(() => isSubmitting = false);
                                        "
                                        :disabled="isSubmitting"
                                        class="disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart-icon lucide-heart">
                                        <path
                                            d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                </button>
                                {{-- ========================================================== --}}
                            @endauth

                            {{-- Tombol Komen (Tidak berubah) --}}
                            <a href="{{ route('post.show', $post) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                                </svg>
                            </a>
                            
                            {{-- Tombol share (Tidak berubah) --}}
                            <button
                                @click="
                                    navigator.clipboard.writeText('{{ route('post.show', $post) }}');
                                    window.dispatchEvent(new CustomEvent('toast-notification', {
                                        detail: {
                                            type: 'success',
                                            message: 'Link copied to clipboard!'
                                        }
                                    }));
                                ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-send-horizontal-icon lucide-send-horizontal">
                                    <path
                                        d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z" />
                                    <path d="M6 12h16" />
                                </svg>
                            </button>
                        </div>

                        {{-- Jumlah Like --}}
                        {{-- ========================================================== --}}
                        {{-- == UBAHAN 3: Ganti jumlah like agar dinamis (pakai x-text) == --}}
                        {{-- ========================================================== --}}
                        <div class="font-bold text-sm mt-2 text-foreground">
                            <span x-text="likeCount"></span> 
                            <span x-text="likeCount === 1 ? 'like' : 'likes'"></span>
                        </div>

                        {{-- Caption (Tidak berubah) --}}
                        <div class="text-sm mt-2">
                            <a href="{{ route('profile.show', $post->user) }}"
                                class="font-bold text-foreground">{{ $post->user->username }}</a>
                            <span class="text-muted-foreground">{{ $post->caption }}</span>
                        </div>
                    </div>
            @endforeach

            {{-- Pagination Links (Tidak berubah) --}}
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