<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\Official;
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

    $query = request('search');

    $users = User::where('role', 'resident')
        ->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return view('admin.users.index', compact('users', 'query'));
})->middleware('auth')->name('admin.users');

// Admin route to view all officials
Route::get('/admin/officials', function () {

    abort_unless(Auth::user()->role === 'admin', 403);

    $query = request('search');

    $officials = Official::where('position', 'like', "%{$query}%")
        ->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%{$query}%")
                    ->orWhere('position', 'like', "%{$query}%")
                    ->orWhere('assignment', 'like', "%{$query}%");
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return view('admin.officials.index', compact('officials', 'query'));
})->middleware('auth')->name('admin.officials');


Route::middleware(['auth'])->group(function () {
    Route::post('/officials', [AdminController::class, 'store_official'])
        ->name('officials.store');
});














// Admin route to send notification to a specific user

Route::post('/send-to-one/{id}', function ($id) {

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
