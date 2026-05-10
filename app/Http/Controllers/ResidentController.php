<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $residents = Resident::orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->get();

        return view('admin.residents.index', compact('residents'));
    }
}
