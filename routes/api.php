<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlotterController;
use App\Http\Controllers\Api\ConcernController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/officials', [UserController::class, 'getOfficials']);
    Route::get('/ordinances', [UserController::class, 'getOrdinances']);
    Route::get('/news', [UserController::class, 'getNews']);

    Route::post('/requests', [RequestController::class, 'store']);
    Route::get('/my-requests', [RequestController::class, 'myRequests']);
    //Admin gets all the Request
    Route::get('/all-requests', [RequestController::class, 'allRequest']);
    //Admin update the status
    Route::put('/requests/{id}/status', [RequestController::class, 'updateStatus']);

    // USER
    Route::post('/concerns', [ConcernController::class, 'store']);
    Route::get('/my-concerns', [ConcernController::class, 'myConcerns']);
    //Admin gets all the concern
    Route::get('/all-concerns', [ConcernController::class, 'allConcerns']);

    // ADMIN
    Route::get('/concerns', [ConcernController::class, 'index']);
    Route::post('/concerns/{id}/status', [ConcernController::class, 'updateStatus']);

    Route::post('/news/{id}/view', [NewsController::class, 'markViewed']);
    Route::get('/unreadNews', [NewsController::class, 'unreadNews']);

    Route::get('/blotters', [BlotterController::class, 'index']);
    Route::post('/blotters', [BlotterController::class, 'store']);
    //Admin gets all the blotters
    Route::get('/all-blotters', [BlotterController::class, 'allindex']);
    //Admin update the status
    Route::put('/blotter/{id}/status', [BlotterController::class, 'updateStatus']);

    // Route::get('/app-status', [AppStatusController::class, 'index']);
});
