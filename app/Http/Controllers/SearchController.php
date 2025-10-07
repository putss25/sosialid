<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $users= collect(); //buat koleksi kosong

        if ($query = $request->query('q')){
            $users = User::where('username', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->get();
        }

        return view('search.index', [
            'users' => $users
        ]);

    }
}
