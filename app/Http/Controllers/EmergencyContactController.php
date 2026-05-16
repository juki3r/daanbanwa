<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function index()
    {
        return response()->json([
            'contacts' => EmergencyContact::where('is_active', 1)
                ->orderBy('priority', 'asc')
                ->get()
        ]);
    }
}
