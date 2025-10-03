<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
      public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', [
            'users' => $users,
        ]);
    }
    public function verifyUser(User $user)
    {
        $user->is_verified = true;
        $user->save();

        return back()->with('status', 'user' . $user->username . 'Has bean verified');
    }
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::user()->id()) {
            return back()->with('error', 'You cannot delete your own account from the admin panel');
        }
          $user->delete();

    return back()->with('status', 'User "' . $user->username . '" has been deleted!');
    }

}
