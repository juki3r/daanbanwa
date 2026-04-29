<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
    ]);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/send-forgot-otp', [AuthController::class, 'sendForgotOtp']);
Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/save-fcm-token', [AuthController::class, 'saveFcmToken']);


//This is for notification
Route::get('/send-to-one/{id}', [AdminController::class, 'sendToOne']);
