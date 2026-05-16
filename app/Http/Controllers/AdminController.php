<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Official;
use App\Models\Resident;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    public function index()
    {
        $totalResidents = Resident::count();

        $male = Resident::where('sex', 'Male')->count();
        $female = Resident::where('sex', 'Female')->count();
        $voters = Resident::where('is_voter', true)->count();
        $pwd = Resident::where('is_pwd', true)->count();
        $occupation = Resident::whereNotNull('occupation')
            ->where('occupation', '!=', '')
            ->count();
        $appuser = User::whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->where('granted', 1)
            ->where('role', 'resident')
            ->count();

        $households = Resident::distinct('household_name')->count('household_name');

        $recentResidents = Resident::latest()->take(5)->get();

        // AGE GROUPS
        $ageData = [
            Resident::whereRaw('TIMESTAMPDIFF(YEAR,birth_date,CURDATE()) BETWEEN 0 AND 17')->count(),
            Resident::whereRaw('TIMESTAMPDIFF(YEAR,birth_date,CURDATE()) BETWEEN 18 AND 59')->count(),
            Resident::whereRaw('TIMESTAMPDIFF(YEAR,birth_date,CURDATE()) >= 60')->count(),
        ];

        // CIVIL STATUS
        $civilData = [
            Resident::where('civil_status', 'Single')->count(),
            Resident::where('civil_status', 'Married')->count(),
            Resident::where('civil_status', 'Widow')->count(),
            Resident::where('civil_status', 'Separated')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalResidents',
            'male',
            'female',
            'voters',
            'pwd',
            'households',
            'recentResidents',
            'ageData',
            'civilData',
            'appuser',
            'occupation'
        ));
    }

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

    //         // 1️⃣ SAVE FIRST (audit trail)
    //         $log = Notification::create([
    //             'title'   => $title,
    //             'body'    => $body,
    //         ]);

    //         // 1️⃣ SEND FIREBASE PUSH
    //         (new \App\Services\FirebaseService)->sendNotification(
    //             $user->fcm_token,
    //             $title,
    //             $body
    //         );

    //         // 2️⃣ SEND SMS
    //         try {
    //             Http::withHeaders([
    //                 'X-API-KEY' => env('SMS_API_KEY')
    //             ])->post('https://carlesppo.com/api/send-sms-api', [
    //                 'phone_number' => $user->phone,
    //                 'message' => "[Daan Banwa ALERT]\n$title\n$body"
    //             ]);
    //         } catch (\Exception $e) {
    //             \Log::error('SMS failed: ' . $e->getMessage());
    //         }

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => "Notification + SMS sent successfully."
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

            // 1️⃣ SAVE NOTIFICATION (USER-BASED)
            Notification::create([
                'user_id' => $user->id,
                'title'   => $title,
                'body' => $body,
                'type'    => 'admin_message',
                'is_read' => false,
            ]);

            // FIREBASE PUSH
            (new \App\Services\FirebaseService)->sendNotification(
                $user->fcm_token,
                $title,
                \Illuminate\Support\Str::limit($body, 160),
                [
                    'screen' => 'Requests',
                    'requests_id' => (string) $user->id,
                ]
            );

            // 3️⃣ SMS
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
