<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blotter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:pending,approved,declined',
        ]);

        $blotter = Blotter::findOrFail($id);

        $blotter->update([
            'status' => strtolower($request->status),
        ]);



        // Get user from request (IMPORTANT FIX)
        $user = User::find($blotter->user_id);

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        if (!$user->fcm_token) {
            return back()->with('error', 'User has no FCM token registered.');
        }

        $title = "Blotter Update !";
        $body  = "Your blotter is " . $request->status;

        (new \App\Services\FirebaseService)->sendNotification(
            $user->fcm_token,
            $title,
            $body,
            [
                'screen' => 'Requests',
                'blotter_id' => (string) $blotter->id,
            ]
        );

        //  SEND SMS
        try {
            Http::withHeaders([
                'X-API-KEY' => env('SMS_API_KEY')
            ])->post('https://carlesppo.com/api/send-sms-api', [
                'phone_number' => $user->phone,
                'message' => "[Daan Banwa ALERT]\n$title\n$body"
            ]);
        } catch (\Exception $e) {
            \Log::error('SMS failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Status updated successfully');
    }
}
