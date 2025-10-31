@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 lg:px-8">
    <h1 class="text-2xl font-semibold mb-6">Pesan (Direct Messages)</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="divide-y divide-gray-200">
            @if ($conversations->isEmpty())
                <p class="text-gray-500 text-center p-6">
                    Anda belum memiliki pesan. Mulai obrolan baru dengan mengunjungi profil seseorang.
                </p>
            @else
                @foreach ($conversations as $conversation)
                    {{-- Kita butuh info 'user lain' di obrolan ini --}}
                    @php
                        $otherUser = $conversation->users->first();
                        $lastMessage = $conversation->messages->first();
                    @endphp

                    @if ($otherUser)
                        <a href="{{ route('chat.show', $otherUser) }}" class="flex items-center p-4 hover:bg-gray-50 transition duration-150">
                            <img src="{{ $otherUser->avatar }}" alt="{{ $otherUser->username }}" class="w-12 h-12 rounded-full mr-4 object-cover">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-md font-semibold text-gray-800">{{ $otherUser->username }}</p>
                                <p class="text-sm text-gray-500 truncate">
                                    @if ($lastMessage)
                                        @if ($lastMessage->user_id == auth()->id())
                                            <span class="font-medium">Anda:</span>
                                        @endif
                                        {{ $lastMessage->body }}
                                    @else
                                        <span class="italic">Belum ada pesan</span>
                                    @endif
                                </p>
                            </div>
                            @if ($lastMessage)
                                <span class="text-xs text-gray-400 self-start">{{ $lastMessage->created_at->diffForHumans(null, true) }}</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection