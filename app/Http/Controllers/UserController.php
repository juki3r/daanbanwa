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
}
