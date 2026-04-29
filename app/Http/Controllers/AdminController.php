<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class AdminController extends Controller
{
    public function sendToOne($id)
    {
        try {
            $user = User::findOrFail($id);

            if (!$user->fcm_token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User has no FCM token registered.'
                ], 400);
            }

            $title = request('title');
            $body  = request('body');

            (new \App\Services\FirebaseService)->sendNotification(
                $user->fcm_token,
                $title,
                $body
            );

            return response()->json([
                'status' => 'success',
                'message' => "Notification sent successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
