<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message; // <-- Pastikan ini ada
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- Pastikan ini ada

class ChatController extends Controller
{
    /**
     * Tampilkan daftar semua obrolan (conversations) milik user.
     */
    public function index()
    {
        $currentUser = Auth::user();

        // Ambil semua obrolan user, urutkan berdasarkan pesan terakhir (jika ada)
        // Kita pakai 'with' (Eager Loading) agar cepat
        $conversations = $currentUser->conversations()
            ->with([
                // Ambil info user lain di obrolan itu
                'users' => function ($query) use ($currentUser) {
                    $query->where('users.id', '!=', $currentUser->id);
                },
                // Ambil 1 pesan terakhir
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->get()
            // Urutkan daftar obrolan berdasarkan pesan terakhir
            ->sortByDesc(function ($convo) {
                return $convo->messages->first()?->created_at;
            });

        // Tampilkan view
        return view('chat.index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Tampilkan satu obrolan spesifik dengan user lain.
     * Jika obrolan belum ada, buat obrolan baru.
     */
    public function show(User $user) // Perhatikan kita menerima $user, bukan $conversation
    {
        $currentUser = Auth::user();

        // 1. Cari obrolan privat (hanya 2 peserta) yang sudah ada
        $conversation = $currentUser->conversations()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function ($query) {
                // Pastikan hanya punya 2 peserta (bukan group chat)
                $query->has('users', 2);
            })
            ->first();

        // 2. Jika obrolan tidak ada, buat baru
        if (!$conversation) {
            $conversation = Conversation::create(); // Buat ruang obrolan baru
            // Masukkan kedua user (kita & dia) ke ruang obrolan
            $conversation->users()->attach([$currentUser->id, $user->id]);
        }

        // 3. Ambil semua pesan dari obrolan ini
        // <-- UBAHAN DIMULAI -->
        // Tambahkan eager load untuk relasi parentMessage
        $messages = $conversation->messages()
            ->with([
                'user', // Ambil info pengirim
                'parentMessage', // Ambil data pesan induk (jika ada)
                'parentMessage.user' // Ambil juga info pengirim pesan induk
            ])
            ->latest()
            ->paginate(50);
        // <-- UBAHAN SELESAI -->

        // Tampilkan view
        return view('chat.show', [
            'conversation' => $conversation,
            'receiver' => $user, // Info orang yang kita ajak chat
            'messages' => $messages
        ]);
    }

    /**
     * Simpan pesan baru ke database.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $currentUser = Auth::user();

        // 1. Validasi
        // <-- UBAHAN DIMULAI -->
        // Simpan hasil validasi ke variabel $validated
        // Tambahkan validasi untuk parent_message_id
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_message_id' => 'nullable|integer|exists:messages,id', // Cek apakah ID pesan induk valid
        ]);
        // <-- UBAHAN SELESAI -->

        // 2. Cek Keamanan: Pastikan user yang login adalah bagian
        //    dari obrolan ini sebelum boleh mengirim pesan.
        if (!$currentUser->conversations()->where('id', $conversation->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Simpan pesan baru
        // <-- UBAHAN DIMULAI -->
        // Gunakan data dari $validated
        $conversation->messages()->create([
            'user_id' => $currentUser->id,
            'body' => $validated['body'],
            'parent_message_id' => $validated['parent_message_id'] ?? null, // Simpan ID induk jika ada
        ]);
        // <-- UBAHAN SELESAI -->

        // 4. Kembalikan ke halaman sebelumnya (halaman obrolan)
        return back();
    }

    /**
     * Hapus pesan.
     */
    public function destroy(Message $message)
    {
        $currentUser = Auth::user();
        $timeLimitMinutes = 5; // Batas waktu edit/hapus dalam menit

        // 1. Keamanan: Pastikan yang menghapus adalah pengirim pesan
        if ($message->user_id !== $currentUser->id) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Keamanan: Pastikan pesan dikirim kurang dari X menit yang lalu
        if (Carbon::now()->diffInMinutes($message->created_at) > $timeLimitMinutes) {
             return back()->with('notification', [
                'type' => 'error',
                'message' => 'Cannot delete message after ' . $timeLimitMinutes . ' minutes.'
            ]);
        }

        // 3. Hapus pesan
        $message->delete();

        // 4. Kembalikan ke halaman obrolan dengan notifikasi
        return redirect()->back()->with('notification', [
            'type' => 'success',
            'message' => 'Message deleted.'
        ]);
    }

    /**
     * Update isi pesan.
     */
    public function update(Request $request, Message $message)
    {
        $currentUser = Auth::user();
        $timeLimitMinutes = 5; // Batas waktu edit/hapus dalam menit

        // 1. Validasi: pastikan isi pesannya tidak kosong
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        // 2. Keamanan: Pastikan yang mengedit adalah pengirim pesan
        if ($message->user_id !== $currentUser->id) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Keamanan: Pastikan pesan dikirim kurang dari X menit yang lalu
        if (Carbon::now()->diffInMinutes($message->created_at) > $timeLimitMinutes) {
             return back()->with('notification', [
                'type' => 'error',
                'message' => 'Cannot edit message after ' . $timeLimitMinutes . ' minutes.'
            ]);
        }

        // 4. Update pesan
        $message->update([
            'body' => $request->body,
        ]);

        // 5. Kembalikan ke halaman obrolan dengan notifikasi
        return redirect()->back()->with('notification', [
            'type' => 'success',
            'message' => 'Message updated.'
        ]);
    }

} // <-- Penutup kurung kurawal class