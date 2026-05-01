<?php

namespace App\Http\Controllers;

use App\Models\Request as BarangayRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        $requests = BarangayRequest::all();

        return view('admin.requests.index', compact('requests'));
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
            ]
        );

        return back()->with('success', 'Status updated successfully');
    }

    public function destroy($id)
    {
        BarangayRequest::findOrFail($id)->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
