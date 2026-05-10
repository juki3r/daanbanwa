<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Ordinance;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // GET OFFICIALS (SECURED)
    public function getOfficials()
    {
        return response()->json([
            'officials' => Official::latest()->get()
        ]);
    }

    // GET ORDINANCES (SECURED)
    public function getOrdinances()
    {
        return response()->json([
            'ordinances' => Ordinance::latest()->get()
        ]);
    }

    // GET NEWS (SECURED)
    public function getNews()
    {
        return response()->json([
            'news' => \App\Models\News::latest()->get()
        ]);
    }



    function index(Request $request)
    {
        $query = $request->input('search');

        $users = \App\Models\User::where('role', 'resident')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.users.index', compact('users', 'query'));
    }
}
