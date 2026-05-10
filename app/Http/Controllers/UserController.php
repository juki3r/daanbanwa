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



    // Controller
    public function index(Request $request)
    {
        $query = User::where('role', 'resident');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    )
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    );
            });
        }

        $users = $query->latest()->paginate(8);

        return view('admin.users.index', compact('users'));
    }

    public function fetch(Request $request)
    {
        $query = User::where('role', 'resident');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    )
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    );
            });
        }

        $users = $query->latest()->paginate(8);

        return response()->json([
            'html' => view('admin.users.partials.rows', compact('users'))->render(),
            'pagination' => (string) $users->links(),
        ]);
    }
}
