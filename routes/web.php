<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route with role-based redirection
Route::get('/dashboard', function () {
    return Auth::user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/admin/dashboard', function () {
    abort_unless(Auth::user()->role === 'admin', 403);

    return view('admin.dashboard');
})->middleware('auth')->name('admin.dashboard');


// Admin route to view all users
Route::get('/admin/users', function () {

    abort_unless(Auth::user()->role === 'admin', 403);

    $users = User::latest()->get();

    return view('admin.users.index', compact('users'));
})->middleware('auth')->name('admin.users');













// Admin route to send notification to a specific user
Route::get('/send-to-one/{id}', function ($id) {
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    return app(App\Http\Controllers\AdminController::class)->sendToOne($id);
})->middleware('auth');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
