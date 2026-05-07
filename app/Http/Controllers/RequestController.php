<?php

namespace App\Http\Controllers;

use App\Models\Request as BarangayRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RequestController extends Controller
{
    // public function index()
    // {
    //     $requests = BarangayRequest::orderBy('created_at', 'desc')->paginate(6);

    //     return view('admin.requests.index', compact('requests'));
    // }

    public function index()
    {
        $requests = BarangayRequest::with('user')
            ->latest()
            ->paginate(6);

        $users = User::orderBy('first_name')->get();

        return view('admin.requests.index', compact('requests', 'users'));
    }

    //Admin store data
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'document_type' => 'required|string',
            'purpose' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'business_nature' => 'nullable|string|max:255',
        ]);

        BarangayRequest::create([
            'user_id' => $request->user_id,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'company_name' => $request->company_name,
            'business_nature' => $request->business_nature,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
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



    public function destroy($id)
    {
        BarangayRequest::findOrFail($id)->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
