<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function users(Request $request)
    {
        $query = User::query();


        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%${search}%")
                    ->orWhere('username', 'like', "%${search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users', [
            'users' => $users,
        ]);
    }
    public function verifyUser(User $user)
    {
        $user->is_verified = true;
        $user->save();

        return back()->with('notification', [
            'type' => 'success',
            'message' => 'user' . $user->username . 'Has bean verified'
        ]);
    }
    public function unverifyUser(User $user)
    {
        $user->is_verified = false;
        $user->email_verified_at = null; // Set kolom verifikasi email menjadi null
        $user->save();

        return back()->with('notification', [
            'type' => 'error',
            'message' => 'user' . $user->username . 'Has bean unverified!'
        ]);
    }


    public function deleteUser(User $user)
    {
        if ($user->is_admin || $user->id === auth()->id()) {
            abort(403);
        }
        $user->delete();
        return back()->with('notification', [
            'type' => 'error',
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Jadikan user sebagai admin.
     * Hanya bisa diakses oleh Super Admin (ID 1).
     */
    public function makeAdmin(User $user)
    {
        // Ini adalah "Satpam Utama"
        if (auth()->id() !== 1) {
            abort(403, 'This action is unauthorized.');
        }

        $user->update(['is_admin' => true]);

        // == DIUBAH ==
        // Kita ganti dari back() menjadi redirect() agar halaman refresh
        return redirect()->route('admin.users.index')->with('notification', [
            'type' => 'success',
            'message' => $user->username . ' is now an admin.'
        ]);
    }

    /**
     * Cabut status admin dari user.
     * Hanya bisa diakses oleh Super Admin (ID 1).
     */
    public function revokeAdmin(User $user)
    {
        // Ini adalah "Satpam Utama"
        if (auth()->id() !== 1) {
            abort(403, 'This action is unauthorized.');
        }

        // Mencegah Super Admin (ID 1) mencabut statusnya sendiri
        if ($user->id === 1) {
            return back()->with('notification', [
                'type' => 'error',
                'message' => 'Cannot revoke Super Admin status.'
            ]);
        }

        $user->update(['is_admin' => false]);

        // == DIUBAH ==
        // Kita ganti dari back() menjadi redirect() agar halaman refresh
        return redirect()->route('admin.users.index')->with('notification', [
            'type' => 'success',
            'message' => 'Admin status revoked from ' . $user->username . '.'
        ]);
    }
    
} // <-- Penutup kurung kurawal class