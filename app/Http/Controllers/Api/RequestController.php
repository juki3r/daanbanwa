<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request as BarangayRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // ✅ GET MY REQUESTS
    public function myRequests()
    {
        $user = Auth::user();

        $requests = BarangayRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        // 🔐 Get authenticated user (requires Sanctum middleware)
        $user = $request->user();

        $request->validate([
            'full_name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'address' => 'required|string',
            'document_type' => 'required|string',
            'purpose' => 'required|string',
            'company_name' => 'nullable|string',
            'business_nature' => 'nullable|string',
        ]);

        $isBusiness = $request->document_type === 'Business Clearance';

        if ($isBusiness) {
            $request->validate([
                'company_name' => 'required|string',
                'business_nature' => 'required|string',
            ]);
        }

        $data = BarangayRequest::create([
            'user_id' => $user->id, // 🔥 IMPORTANT (track who sent request)

            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'company_name' => $request->company_name,
            'business_nature' => $request->business_nature,
        ]);

        return response()->json([
            'message' => 'Request submitted successfully',
            'data' => $data
        ], 201);
    }

    // GET /api/requests/{id}
    public function show($id)
    {
        return response()->json(
            BarangayRequest::findOrFail($id)
        );
    }
}
