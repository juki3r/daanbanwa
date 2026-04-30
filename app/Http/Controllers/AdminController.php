<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Official;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    public function sendToOne($id)
    {
        try {
            $user = User::findOrFail($id);

            if (!$user->fcm_token) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User has no FCM token registered.'
                ], 400);
            }

            $title = request('title');
            $body  = request('body');

            // 1️⃣ SAVE FIRST (audit trail)
            $log = Notification::create([
                'title'   => $title,
                'body'    => $body,
            ]);

            // 1️⃣ SEND FIREBASE PUSH
            (new \App\Services\FirebaseService)->sendNotification(
                $user->fcm_token,
                $title,
                $body
            );

            // 2️⃣ SEND SMS
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

            return response()->json([
                'status'  => 'success',
                'message' => "Notification + SMS sent successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function store_official(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'regex:/^09\d{9}$/', //  strict PH format
                'unique:officials,phone_number'
            ],
            'position'     => 'required|string|max:255',
            'assignment'   => 'nullable|string|max:255',
        ]);

        Official::create([
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'position'     => $request->position,
            'assignment'   => $request->assignment,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Official added successfully.');
    }
}
