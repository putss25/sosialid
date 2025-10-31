@extends('layouts.app')

@section('content')
{{-- ========================================================== --}}
{{-- == UBAHAN 1: Tambahkan x-data global untuk reply == --}}
{{-- ========================================================== --}}
<div class="max-w-4xl mx-auto h-[calc(100vh-120px)] lg:h-[calc(100vh-80px)] flex flex-col mt-0 lg:mt-6"
     x-data="{ replyingTo: null }"> {{-- Ini akan menyimpan data: { id, user, body } --}}
    
    {{-- Header Chat (Tidak berubah) --}}
    <div class="bg-background shadow-md rounded-none lg:rounded-t-lg p-4 flex items-center border-b border-border">
        <a href="{{ route('profile.show', $receiver) }}" class="flex items-center hover:opacity-80 transition">
            <img src="{{ $receiver->avatar }}" alt="{{ $receiver->username }}" class="w-10 h-10 rounded-full mr-3 object-cover">
            <span class="text-lg font-semibold text-foreground">{{ $receiver->username }}</span>
        </a>
        <a href="{{ route('chat.index') }}" class="ml-auto text-sm text-muted-foreground hover:text-foreground transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            <span class="hidden sm:inline">Back</span>
        </a>
    </div>

    {{-- Area Chat Messages --}}
    <div id="chat-messages" class="flex-1 bg-muted-background p-4 lg:p-6 overflow-y-auto">
        <div class="flex flex-col space-y-3">
            {{-- Urutkan pesan dari yang terlama ke terbaru --}}
            @forelse ($messages->sortBy('created_at') as $message)
                
                {{-- Pesan dari pengguna yang login (kanan) --}}
                @if ($message->user_id == auth()->id())
                    <div class="flex justify-end" 
                         x-data="{ showActions: false, isEditing: false }"
                         @mouseover="showActions = true" 
                         @mouseleave="showActions = false"> 
                        <div class="flex items-end gap-2 max-w-[85%] sm:max-w-xs lg:max-w-md">
                            
                            {{-- Tombol Aksi (Edit, Delete, Reply) --}}
                            <div x-show="showActions && !isEditing"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-90"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-90"
                                 class="flex items-center space-x-1 mb-1">
                                
                                {{-- ========================================================== --}}
                                {{-- == UBAHAN 2: Tambahkan Tombol Reply == --}}
                                {{-- ========================================================== --}}
                                <button @click="replyingTo = { id: {{ $message->id }}, user: '{{ $message->user->username }}', body: '{{ Str::limit(addslashes($message->body), 30) }}' }; $nextTick(() => $refs.messageInput.focus())"
                                        class="text-gray-400 hover:text-green-500 p-1 rounded-full hover:bg-green-100" title="Reply">
                                    <x-lucide-reply class="w-4 h-4" />
                                </button>

                                @if (Carbon\Carbon::now()->diffInMinutes($message->created_at) <= 5)
                                    {{-- Tombol Edit --}}
                                    <button @click="isEditing = true; $nextTick(() => $refs.editInput_{{ $message->id }}.focus())" 
                                            class="text-gray-400 hover:text-blue-500 p-1 rounded-full hover:bg-blue-100" title="Edit Message">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </button>
                                    {{-- Form Delete --}}
                                    <form action="{{ route('chat.destroy', $message) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this message?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-red-100" title="Delete Message">
                                            <x-lucide-trash-2 class="w-4 h-4" /> 
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- Bubble Chat Anda --}}
                            <div class="bg-primary text-white rounded-2xl rounded-tr-sm py-2.5 px-4 shadow-sm relative">
                                
                                {{-- ========================================================== --}}
                                {{-- == UBAHAN 3: Tampilkan Kutipan Balasan == --}}
                                {{-- ========================================================== --}}
                                @if ($message->parentMessage)
                                <a href="#message-{{$message->parentMessage->id}}" class="block border-l-2 border-blue-300 pl-2 mb-1.5 opacity-80 hover:opacity-100 text-xs italic">
                                     <strong>{{ $message->parentMessage->user->username }}:</strong> {{ Str::limit($message->parentMessage->body, 50) }}
                                </a>
                                @endif

                                {{-- Tampilkan Teks Pesan (jika tidak sedang edit) --}}
                                <p x-show="!isEditing" class="text-sm break-words" id="message-{{$message->id}}">{{ $message->body }}</p>
                
                                {{-- Tampilkan Form Edit (jika sedang edit) --}}
                                <form x-show="isEditing" x-cloak action="{{ route('chat.update', $message) }}" method="POST" 
                                      @keydown.escape.window="isEditing = false"
                                      x-data="{ editBody: `{{ $message->body }}` }"> {{-- Hati-hati dengan tanda kutip --}}
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="body" x-ref="editInput_{{ $message->id }}"
                                              class="text-sm bg-blue-600 rounded-md p-1 w-full focus:outline-none focus:ring-1 focus:ring-white min-h-[50px] resize-none" 
                                              x-model="editBody"
                                              required></textarea>
                                    <div class="text-xs mt-2 flex justify-end space-x-2">
                                        <button type="button" @click="isEditing = false" class="hover:underline">Cancel</button>
                                        <button type="submit" class="font-semibold hover:underline">Save</button>
                                    </div>
                                </form>
                
                                {{-- Tampilkan Waktu (jika tidak sedang edit) --}}
                                <span x-show="!isEditing" class="text-xs opacity-75 mt-1 block text-right">
                                    {{ $message->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- ========================================================== --}}
                    {{-- == UBAHAN 4: Tambahkan Tombol Reply untuk Lawan Bicara == --}}
                    {{-- ========================================================== --}}
                    <div class="flex justify-start" 
                         x-data="{ showActions: false }"
                         @mouseover="showActions = true" 
                         @mouseleave="showActions = false"> 
                         <div class="flex items-end gap-2 max-w-[85%] sm:max-w-xs lg:max-w-md">
                            {{-- Bubble Chat Dia --}}
                            <div class="bg-background shadow-sm rounded-2xl rounded-tl-sm py-2.5 px-4">
                                {{-- Tampilkan Kutipan Balasan --}}
                                @if ($message->parentMessage)
                                <a href="#message-{{$message->parentMessage->id}}" class="block border-l-2 border-gray-400 pl-2 mb-1.5 opacity-70 hover:opacity-100 text-xs italic">
                                     <strong>{{ $message->parentMessage->user->username }}:</strong> {{ Str::limit($message->parentMessage->body, 50) }}
                                </a>
                                @endif
                                
                                <p class="text-sm text-foreground break-words" id="message-{{$message->id}}">{{ $message->body }}</p>
                                <span class="text-xs text-muted-foreground mt-1 block text-right">
                                    {{ $message->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>

                            {{-- Tombol Aksi (Hanya Reply) --}}
                            <div x-show="showActions" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-90"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="flex items-center space-x-1 mb-1">
                                <button @click="replyingTo = { id: {{ $message->id }}, user: '{{ $message->user->username }}', body: '{{ Str::limit(addslashes($message->body), 30) }}' }; $nextTick(() => $refs.messageInput.focus())"
                                        class="text-gray-400 hover:text-green-500 p-1 rounded-full hover:bg-green-100" title="Reply">
                                    <x-lucide-reply class="w-4 h-4" />
                                </button>
                            </div>
                         </div>
                    </div>
                @endif

            @empty
                {{-- ... (Pesan 'No messages yet', tidak berubah) ... --}}
            @endforelse
        </div>
    </div>

    {{-- Form Input Pesan --}}
    <div class="bg-background shadow-md rounded-none lg:rounded-b-lg p-3 lg:p-4 border-t border-border">
        {{-- ========================================================== --}}
        {{-- == UBAHAN 5: Tampilkan Indikator "Replying to..." == --}}
        {{-- ========================================================== --}}
        <div x-show="replyingTo" x-cloak class="text-xs mb-2 border-l-2 border-green-500 pl-2 text-muted-foreground bg-muted-background p-2 rounded-md">
             Replying to <strong class="text-foreground" x-text="replyingTo?.user"></strong>: 
             "<span class="italic" x-text="replyingTo?.body"></span>"
             <button @click="replyingTo = null" class="ml-2 text-red-500 hover:text-red-700 font-bold" title="Cancel Reply">&times;</button>
        </div>

        <form action="{{ route('chat.store', $conversation) }}" method="POST"
            x-data="{ isSubmitting: false, message: '' }"
            @submit="isSubmitting = true"> 
            @csrf
            
            {{-- ========================================================== --}}
            {{-- == UBAHAN 6: Tambahkan Hidden Input untuk ID Reply == --}}
            {{-- ========================================================== --}}
            <input type="hidden" name="parent_message_id" :value="replyingTo?.id">
            
            @error('body')
                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
            @enderror
            
            <div class="flex items-center gap-2">
                <input type="text"
                    name="body"
                    x-model="message"
                    x-ref="messageInput" {{-- Tambahkan x-ref di sini --}}
                    class="flex-1 bg-muted-background border border-border rounded-full py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                    placeholder="Type a message..."
                    autocomplete="off"
                    required>
                
                <button type="submit"
                    :disabled="isSubmitting || (message.trim() === '' && !replyingTo)" {{-- Sesuaikan disabled --}}
                    class="bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-5 rounded-full disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center min-w-[70px]">
                    <span x-show="!isSubmitting">Send</span>
                    <span x-show="isSubmitting" class="flex items-center">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script untuk auto-scroll ke bawah saat halaman dimuat --}}
@push('scripts')
<script>
    // Auto scroll ke pesan terbaru saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
@endpush
@endsection