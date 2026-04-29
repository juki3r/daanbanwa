<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'purok' => ['required', 'string'],

            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/', // 👈 strict PH format
                'unique:users,phone'
            ],

            'password' => ['required', 'min:6'],
        ], [
            'phone.regex' => 'Phone number must start with 09 and be 11 digits.',
            'phone.unique' => 'This phone number is already registered.',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'purok' => $validated['purok'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'resident',
        ]);

        $token = $user->createToken('daanbanwa-mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('daanbanwa-mobile')->plainTextToken;

        // ✅ IF VERIFIED → DASHBOARD
        if ($user->phone_verified) {
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'phone_verified' => true,
                'next' => 'dashboard',
            ]);
        }

        // ❌ NOT VERIFIED → OTP FLOW

        // check cooldown (avoid spam)
        if ($user->otp_sent_at && Carbon::parse($user->otp_sent_at)->diffInSeconds(now()) < 60) {
            $otp = $user->otp_code; // reuse existing OTP
        } else {
            $otp = rand(100000, 999999);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(5),
                'otp_sent_at' => now(),
            ]);

            // send SMS
            Http::withHeaders([
                'X-API-KEY' => "qHafeGIG2dWbb5QEKdW1jR2J0rhNbIr0wjeyfkeY",
            ])->post('https://carlesppo.com/api/send-sms-api', [
                'phone_number' => $user->phone,
                'message' => "Your OTP is: $otp"
            ]);
        }

        return response()->json([
            'message' => 'OTP sent',
            'user' => $user,
            'token' => $token,
            'phone_verified' => false,
            'next' => 'otp'
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required'],
            'otp' => ['required'],
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if (!$user->otp_code) {
            return response()->json([
                'message' => 'No OTP requested'
            ], 400);
        }

        if (now()->gt($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP expired'
            ], 400);
        }

        if ($user->otp_code !== $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 401);
        }

        // ✅ mark verified
        $user->update([
            'phone_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // login token after verification
        $token = $user->createToken('daanbanwa-mobile')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /* ================================
        1. SEND FORGOT OTP
    ================================= */
    public function sendForgotOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Phone number not found'
            ], 404);
        }

        // generate 6 digit OTP
        $otp = rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // SEND SMS (your API)
        Http::withHeaders([
            'X-API-KEY' => "qHafeGIG2dWbb5QEKdW1jR2J0rhNbIr0wjeyfkeY"
        ])->post('https://carlesppo.com/api/send-sms-api', [
            'phone_number' => $user->phone,
            'message' => "Your reset OTP is: $otp"
        ]);

        return response()->json([
            'message' => 'OTP sent successfully'
        ]);
    }

    /* ================================
        2. VERIFY RESET OTP
    ================================= */
    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->otp_code !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP expired'], 401);
        }

        return response()->json([
            'message' => 'OTP verified',
            'reset_token' => encrypt($user->id)
        ]);
    }

    /* ================================
        3. RESET PASSWORD
    ================================= */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required',
            'password' => 'required|min:6'
        ]);

        $userId = decrypt($request->reset_token);

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'message' => 'Password reset successful'
        ]);
    }

    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'token' => 'required',
        ]);

        User::where('id', $request->user_id)
            ->update([
                'fcm_token' => $request->token
            ]);

        return response()->json([
            'message' => 'FCM token saved'
        ]);
    }
}
