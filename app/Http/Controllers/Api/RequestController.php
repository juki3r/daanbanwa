<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request as BarangayRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

    //Admin gets all the request
    public function allRequest()
    {
        $requests = BarangayRequest::orderBy('created_at', 'desc')->get();

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        // 🔐 Get authenticated user (requires Sanctum middleware)
        $user = $request->user();
        // Get admin user
        $admins = User::where('role', 'admin')->get();
        $firebase = new \App\Services\FirebaseService;

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

        //Add notification here for admin/staff/captain
        $title = 'New Document Request';
        $body = $request->document_type . ', ' . $request->full_name . ' is requesting document for ' . $request->purpose;
        $message = "[Daan Banwa ALERT]\n{$title}\n{$body}";
        // Send to admin
        foreach ($admins as $admin) {
            // 1️⃣ Send Push Notification
            if ($admin->fcm_token) {
                $firebase->sendNotification(
                    $admin->fcm_token,
                    $title,
                    $body
                );
            }

            // 2️⃣ Send SMS
            if ($admin->phone) {
                try {
                    Http::withHeaders([
                        'X-API-KEY' => env('SMS_API_KEY')
                    ])->post('https://carlesppo.com/api/send-sms-api', [
                        'phone_number' => $admin->phone,
                        'message' => $message
                    ]);
                } catch (\Exception $e) {
                    \Log::error("SMS failed for admin {$admin->id}: " . $e->getMessage());
                }
            }
        }




        return response()->json([
            'message' => 'Request submitted successfully',
            'data' => $data
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        // Get request record
        $req = BarangayRequest::findOrFail($id);

        // Update status
        $req->update([
            'status' => $request->status
        ]);

        // Get user from request (IMPORTANT FIX)
        $user = User::find($req->user_id);

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        if (!$user->fcm_token) {
            return back()->with('error', 'User has no FCM token registered.');
        }

        $title = "Certification Request Update";
        $body  = "Your request has been " . $request->status;

        (new \App\Services\FirebaseService)->sendNotification(
            $user->fcm_token,
            $title,
            $body,
            [
                'screen' => 'Requests',
                'requests_id' => (string) $req->id,
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





    // GET /api/requests/{id}
    public function show($id)
    {
        return response()->json(
            BarangayRequest::findOrFail($id)
        );
    }
}
