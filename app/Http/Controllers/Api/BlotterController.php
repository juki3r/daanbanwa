<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blotter;
use Illuminate\Http\Request;

class BlotterController extends Controller
{
    public function index(Request $request)
    {
        $blotters = Blotter::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($blotters);
    }
    //Admin gets all blotters
    public function allindex(Request $request)
    {
        $blotters = Blotter::whereNotIn('status', ['approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $blotters
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'complainant_name' => 'required|string|max:255',
            'statement' => 'required|string',
        ]);

        $countToday = Blotter::whereDate('created_at', today())->count() + 1;

        $caseNumber = 'BLT-' . now()->format('Ymd') . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $blotter = Blotter::create([
            'user_id' => $request->user()->id,
            'complainant_name' => $request->complainant_name,
            'statement' => $request->statement,
            'status' => 'pending',
            'case_number' => $caseNumber,
        ]);

        return response()->json([
            'message' => 'Blotter report submitted successfully.',
            'data' => $blotter,
        ], 201);
    }
}
