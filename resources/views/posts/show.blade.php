@extends('layouts.app')

@section('content')
    <div class="flex lg:h-[100vh] justify-center items-center ">

        <button onclick="history.back()"
            class="hidden lg:absolute top-8 right-10 text-foreground bg-background/20 size-7 rounded-full rounded-tl-lg backdrop-blur-lg"><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x-icon lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg></button>
            
        {{-- ========================================================== --}}
        {{-- == UBAHAN 1: Tambahkan x-data untuk SEMUA state dinamis == --}}
        {{-- ========================================================== --}}
        <div @click="history.back()" @keydown.escape.window="history.back()"
            class="lg:h-[80%] relative mx-auto mt-6 bg-background rounded-lg shadow-none lg:shadow-lg flex flex-col lg:flex-row h-fit "
            x-data="{
                {{-- State untuk Like --}}
                isLiked: {{ auth()->check() && auth()->user()->likes->contains($post) ? 'true' : 'false' }},
                likeCount: {{ $post->likes_count }},
                isSubmittingLike: false,
                
                {{-- State untuk Komen --}}
                comments: {{ $post->comments->keyBy('id')->toJson() }}, {{-- Simpan sebagai objek --}}
                commentCount: {{ $post->comments_count }},
                newCommentBody: '',
                isSubmittingComment: false
             }">

            {{-- Header Mobile --}}
            <div class="flex justify-between lg:hidden items-center pb-4 px-3">
                <div class="flex items-center">
                    <a @click.stop href="{{ route('profile.show', $post->user) }}">
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div class="ml-4">
                        <a @click.stop href="{{ route('profile.show', $post->user) }}"
                            class="text-sm font-bold text-foreground">{{ $post->user->username }}</a>
                    </div>
                    {{-- Waktu Post --}}
                    <div class="ml-2 text-xs text-muted-foreground border-border">
                        <a @click.stop href="{{ route('post.show', $post) }}">
                            {{ $post->created_at->diffForHumans() }}
                        </a>
                    </div>
                </div>
                <div class="flex ">
                    @if (auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->is_admin))
                        <div x-data="{ open: false, copied: false }" class="relative ml-auto">
                            {{-- Tombol Pemicu Dropdown (Ikon Tiga Titik) --}}
                            <button @click.stop="open = !open"
                                class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>

                            {{-- Panel Dropdown --}}
                            <div x-show="open" @click.away="open = false"
                                @click.stop {{-- .stop agar klik di dalam dropdown tidak menutup modal --}}
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-muted-background rounded-md shadow-xl z-20 origin-top-right  focus:outline-none"
                                x-cloak>
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <button
                                        @click="
                            navigator.clipboard.writeText('{{ route('post.show', $post) }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                            open = false;
                            "
                                        class="w-full text-left block px-4 py-2 text-sm text-foreground   ">
                                        <span x-show="!copied">Copy Link</span>
                                        <span x-show="copied" class="text-green-500 font-semibold">Copied!</span>
                                    </button>

                                    <a href="{{ route('posts.edit', $post) }}"
                                        class="block px-4 py-2 text-sm text-foreground dark: text-secondary] "
                                        role="menuitem">
                                        Edit
                                    </a>

                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" role="menuitem">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-sm text-destructive "
                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Kolom Gambar --}}
            <div class="relative" @click.stop> {{-- .stop agar klik gambar tidak close modal --}}
                <img src="{{ $post->image }}" alt="{{ $post->caption }}" class="w-full h-full object-cover  min-w-full">
            </div>

            {{-- Kolom Informasi --}}
            <div class="lg:max-w-[600px] lg:w-1/3 mx-3 flex flex-col justify-between">
                {{-- Header Post (Desktop) --}}
                <div class="hidden lg:flex items-center pb-4 border-b border-border">
                    <a @click.stop href="{{ route('profile.show', $post->user) }}">
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                            class="w-10 h-10 rounded-full object-cover">
                    </a>
                    <div class="ml-4">
                        <a @click.stop href="{{ route('profile.show', $post->user) }}"
                            class="text-sm font-bold text-foreground">{{ $post->user->username }}</a>
                    </div>
                    <div class="ml-2 text-xs text-muted-foreground border-border">
                        <a @click.stop href="{{ route('post.show', $post) }}">
                            {{ $post->created_at->diffForHumans() }}
                        </a>
                    </div>
                    {{-- DROPDOWN BTN (Desktop) --}}
                    @if (auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->is_admin))
                        <div x-data="{ open: false, copied: false }" class="relative ml-auto">
                            <button @click.stop="open = !open"
                                class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                @click.stop
                                class="absolute right-0 mt-2 w-48 bg-muted-background rounded-md shadow-xl z-20 origin-top-right  focus:outline-none"
                                x-cloak>
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <button
                                        @click="
                            navigator.clipboard.writeText('{{ route('post.show', $post) }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                            open = false;
                            "
                                        class="w-full text-left block px-4 py-2 text-sm text-foreground ">
                                        <span x-show="!copied">Copy Link</span>
                                        <span x-show="copied" class="text-green-500 font-semibold">Copied!</span>
                                    </button>
                                    <a href="{{ route('posts.edit', $post) }}"
                                        class="block px-4 py-2 text-sm text-foreground " role="menuitem">
                                        Edit
                                    </a>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" role="menuitem">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-sm text-destructive "
                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                {{-- ========================================================== --}}
                {{-- == UBAHAN 2: Ganti @foreach dengan <template x-for> == --}}
                {{-- ========================================================== --}}
                <div id="comment-section" class="space-y-4 max-h-60 lg:max-h-none lg:h-full my-4 overflow-y-auto w-full overflow-x-hidden " @click.stop>
                    {{-- Tampilkan Caption sebagai "komentar" pertama (jika ada) --}}
                    @if ($post->caption)
                        <div class="flex items-start space-x-3">
                            <a href="{{ route('profile.show', $post->user) }}" class="flex-shrink-0">
                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->username }}'s avatar"
                                    class="w-10 h-10 rounded-full object-cover">
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.show', $post->user) }}"
                                    class="font-bold text-foreground">{{ $post->user->username }}</a>
                                <span
                                    class="text-xs text-muted-foreground mt-1">{{ $post->created_at->diffForHumans() }}
                                </span>
                                <div class="text-foreground break-all">{{ $post->caption }}</div>
                            </div>
                        </div>
                    @endif
                
                    {{-- Daftar Komentar Dinamis --}}
                    <template x-for="comment in Object.values(comments).sort((a,b) => new Date(a.created_at) - new Date(b.created_at))" :key="comment.id">
                        <div class="flex items-start space-x-3">
                            <a :href="`/${comment.user.username}`" class="flex-shrink-0">
                                <img :src="comment.user.avatar" :alt="comment.user.username + '\'s avatar'"
                                    class="w-10 h-10 rounded-full object-cover">
                            </a>
                            <div class="flex-1 min-w-0">
                                <a :href="`/${comment.user.username}`"
                                    class="font-bold text-foreground" x-text="comment.user.username"></a>
                                <span
                                    class="text-xs text-muted-foreground mt-1" 
                                    {{-- Gunakan JS untuk format waktu agar seragam --}}
                                    x-text="new Date(comment.created_at).toLocaleString('default', { month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' })">
                                </span>
                                <div class="text-foreground break-all" x-text="comment.body"></div>
                            </div>
                        </div>
                    </template>
                </div>


                <div @click.stop> {{-- .stop agar klik di area bawah tidak close modal --}}

                    {{-- Aksi (Like, Comment) & Caption --}}
                    <div class="mt-4">
                        <div class="flex items-center space-x-3">
                            @auth
                                {{-- Tombol Like/Unlike (Sudah dinamis, tidak berubah) --}}
                                <button type="button" x-show="isLiked" @click.stop="isSubmittingLike=true; axios.post('{{ route('post.unlike', $post) }}').then(res => { isLiked = false; likeCount = res.data.likeCount; }).finally(() => isSubmittingLike = false)" :disabled="isSubmittingLike" class="disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="fill-accent stroke-accent">
                                        <path
                                            d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                </button>
                                <button type="button" x-show="!isLiked" @click.stop="isSubmittingLike=true; axios.post('{{ route('post.like', $post) }}').then(res => { isLiked = true; likeCount = res.data.likeCount; }).finally(() => isSubmittingLike = false)" :disabled="isSubmittingLike" class="disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart-icon lucide-heart">
                                        <path
                                            d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                </button>
                            @endauth

                            {{-- Tombol Comment (Arahkan ke #comment-form) --}}
                            <a @click.stop href="#comment-form">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                                </svg>
                            </a>
                            {{-- Tombol Share (Tidak berubah) --}}
                            <button
                                @click.stop="
                                    navigator.clipboard.writeText('{{ route('post.show', $post) }}');
                                    window.dispatchEvent(new CustomEvent('toast-notification', {
                                        detail: {
                                            type: 'success',
                                            message: 'Link copied to clipboard!'
                                        }
                                    }));">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-send-horizontal-icon lucide-send-horizontal">
                                    <path
                                        d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z" />
                                    <path d="M6 12h16" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex gap-2 items-center mt-2">
                            {{-- Jumlah Like (Sudah dinamis) --}}
                            <div class="font-bold text-sm  ">
                                <span x-text="likeCount"></span> 
                                <span x-text="likeCount === 1 ? 'like' : 'likes'"></span>
                            </div>
                            <span class="text-sm">|</span>

                            {{-- ========================================================== --}}
                            {{-- == UBAHAN 3: Ganti jumlah komen agar dinamis == --}}
                            {{-- ========================================================== --}}
                            <div class="font-bold text-sm  ">
                                <span x-text="commentCount"></span> 
                                <span x-text="commentCount === 1 ? 'Comment' : 'Comments'"></span>
                            </div>

                        </div>

                        {{-- Caption (Sudah dipindah ke atas, di dalam list komen) --}}
                        {{-- <div class="text-sm mt-2"> ... </div> --}}
                    </div>
                    
                    {{-- ========================================================== --}}
                    {{-- == UBAHAN 4: Modifikasi Form Komentar == --}}
                    {{-- ========================================================== --}}
                    <div id="comment-form" class="border-t border-border pt-3 mt-3  mb-3">
                        @auth
                            <form action="{{ route('comments.store', $post) }}" method="POST"
                                {{-- Ganti x-data dan @submit --}}
                                @submit.prevent="
                                    isSubmittingComment = true;
                                    axios.post('{{ route('comments.store', $post) }}', {
                                        body: newCommentBody
                                    })
                                    .then(response => {
                                        {{-- Tambahkan komentar baru ke 'comments' --}}
                                        comments[response.data.newComment.id] = response.data.newComment; 
                                        commentCount = response.data.commentCount; {{-- Update jumlah --}}
                                        newCommentBody = ''; {{-- Kosongkan textarea --}}
                                        
                                        {{-- Auto-resize textarea kembali ke 1 baris --}}
                                        $refs.commentTextarea.style.height = 'auto';

                                        {{-- Scroll ke bawah setelah komentar baru ditambahkan --}}
                                        $nextTick(() => {
                                            const commentSection = document.getElementById('comment-section');
                                            commentSection.scrollTop = commentSection.scrollHeight;
                                        });
                                    })
                                    .catch(error => {
                                        console.error(error);
                                        window.dispatchEvent(new CustomEvent('toast-notification', {
                                            detail: { type: 'error', message: 'Failed to post comment.' }
                                        }));
                                    })
                                    .finally(() => isSubmittingComment = false);
                                "
                                @click.stop>
                                @csrf
                                <div class="flex w-full space-x-2 items-center justify-center ">
                                    <textarea name="body" rows="1"
                                        x-model="newCommentBody" {{-- Tambahkan x-model --}}
                                        x-ref="commentTextarea" {{-- Tambahkan x-ref --}}
                                        class="w-full   text-foreground focus:outline-none focus:ring-0 text-sm  resize-none overflow-hidden"
                                        placeholder="Add a comment..."
                                        oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px'"></textarea>
                                    @error('body')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <button type="submit" :disabled="isSubmittingComment || newCommentBody.trim() === ''"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 
                                               disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <span x-show="!isSubmittingComment">Post</span>
                                        <span x-show="isSubmittingComment" style="display: none;">Posting...</span></button>
                                </div>
                            </form>
                        @endauth
                        @guest
                            <p class="text-sm text-muted-foreground">
                                <a href="{{ route('login') }}" class="text-primary font-semibold">Log in</a> to post a
                                comment.
                            </p>
                        @endguest
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection