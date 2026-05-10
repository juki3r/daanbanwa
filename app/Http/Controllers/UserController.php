<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Ordinance;
use App\Models\User;
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



    public function index(Request $request)
    {
        $query = User::where('role', 'resident')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('phone', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', "%{$request->search}%");
                    });
            });
        }

        $users = $query->latest()->paginate(8);

        return view('admin.users.index', compact('users'));
    }

    public function fetch(Request $request)
    {
        $query = User::where('role', 'resident')->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('phone', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', "%{$request->search}%");
                    });
            });
        }

        $users = $query->latest()->paginate(8);

        return response()->json([
            'html' => view('admin.users.partials.rows', compact('users'))->render(),
            'pagination' => (string) $users->links(),
        ]);
    }
}
