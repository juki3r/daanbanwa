<?php

namespace App\Http\Controllers;

use App\Models\Concern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConcernController extends Controller
{
    public function index()
    {
        $concerns = Concern::all();
        return view('admin.concern.index', compact('concerns'));
    }

    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:received,under_review,in_progress,resolved,rejected'
        ]);

        $concern = Concern::findOrFail($id);

        $concern->update([
            'status' => $request->status
        ]);



        // Get user from request (IMPORTANT FIX)
        $user = User::find($concern->user_id);

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        if (!$user->fcm_token) {
            return back()->with('error', 'User has no FCM token registered.');
        }

        $title = "Concern Update !";
        $body  = "Your concern is" . $request->status;

        (new \App\Services\FirebaseService)->sendNotification(
            $user->fcm_token,
            $title,
            $body,
            [
                'screen' => 'Concerns',
                'concerns_id' => (string) $concern->id,
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
        Concern::findOrFail($id)->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
