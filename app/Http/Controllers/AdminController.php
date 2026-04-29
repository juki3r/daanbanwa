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
            // ✅ Find user or fail
            $user = User::findOrFail($id);


            // ✅ Ensure FCM token exists
            if (!$user->fcm_token) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User has no FCM token registered.'
                ], 400);
            }

            // ✅ Send notification via FirebaseService
            (new \App\Services\FirebaseService)->sendNotification(
                $user->fcm_token,
                'Information',
                'Garbage collection schedule has been updated. Please check the app for details.'

            );

            return response()->json([
                'status'  => 'success',
                'message' => "Notification sent to user ID {$id} successfully."
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send notification.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
