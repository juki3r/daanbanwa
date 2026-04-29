<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    // public function sendToOne($id)
    // {
    //     try {
    //         $user = User::findOrFail($id);

    //         if (!$user->fcm_token) {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'User has no FCM token registered.'
    //             ], 400);
    //         }

    //         $title = request('title');
    //         $body  = request('body');

    //         (new \App\Services\FirebaseService)->sendNotification(
    //             $user->fcm_token,
    //             $title,
    //             $body
    //         );

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => "Notification sent successfully."
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

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
}
