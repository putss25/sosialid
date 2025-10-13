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

        if($request->has('q')){
            $search = $request->input('q');
            $query->where(function($q) use ($search){
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
        if ($user->id === Auth::user()->id()) {
            return back()->with('error', 'You cannot delete your own account from the admin panel');
        }
          $user->delete();

        return back()->with('notification', [
            'type' => 'error',
            'message' => 'status', 'User "' . $user->username . '" has been deleted!'
        ]);
    }

}
