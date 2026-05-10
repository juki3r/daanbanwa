<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::query();

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('middle_name', 'like', "%{$request->search}%")
                    ->orWhere('purok', 'like', "%{$request->search}%")
                    ->orWhere('household_name', 'like', "%{$request->search}%");
            });
        }

        // 📊 PAGINATION + ALPHABETICAL SORT
        $residents = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(7)
            ->withQueryString();

        return view('admin.residents.index', compact('residents'));
    }

    public function fetch(Request $request)
    {
        $query = Resident::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('middle_name', 'like', "%{$request->search}%")
                    ->orWhere('purok', 'like', "%{$request->search}%")
                    ->orWhere('household_name', 'like', "%{$request->search}%");
            });
        }

        $residents = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(7);

        return response()->json([
            'html' => view('admin.residents.partials.rows', compact('residents'))->render(),
            'pagination' => (string) $residents->links(),
        ]);
    }
}
