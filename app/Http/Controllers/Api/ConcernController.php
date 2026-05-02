<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class ConcernController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
        ]);

        $user = $request->user();

        $concern = Concern::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'submitted',
            'progress' => 10,
        ]);

        // Get admin user
        $admins = User::where('role', 'admin')->get();
        $firebase = new \App\Services\FirebaseService;

        // Notification content
        $title = 'New Concern Submitted';
        $body = "{$user->name} submitted a concern: {$request->title} at {$request->location}";
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
                    Log::error("SMS failed for admin {$admin->id}: " . $e->getMessage());
                }
            }
        }



        return response()->json([
            'message' => 'Concern submitted successfully',
            'concern' => $concern
        ]);
    }

    public function myConcerns(Request $request)
    {
        return response()->json([
            'concerns' => Concern::where('user_id', $request->user()->id)
                ->latest()
                ->get()
        ]);
    }

    //Admin gets all Concerns
    public function allConcerns(Request $request)
    {
        $concerns = Concern::orderBy('created_at', 'desc')->get();

        return response()->json([
            'concerns' => $concerns
        ]);
    }

    private function getProgress($status)
    {
        return match ($status) {
            'submitted' => 10,
            'received' => 25,
            'under_review' => 50,
            'in_progress' => 80,
            'resolved' => 100,
            'rejected' => 0,
            default => 10,
        };
    }

    public function updateStatus(Request $request, $id)
    {
        $concern = Concern::findOrFail($id);

        $concern->status = $request->status;
        $concern->progress = $this->getProgress($request->status);
        $concern->admin_reply = $request->admin_reply;

        $concern->save();

        return response()->json([
            'message' => 'Concern updated',
            'concern' => $concern
        ]);
    }
}
