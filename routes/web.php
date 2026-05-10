<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrdinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ResidentController;
use App\Models\Concern;
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




Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {


    Route::post('/officials', [AdminController::class, 'store_official'])
        ->name('officials.store');

    Route::get('/ordinances', [OrdinanceController::class, 'index'])->name('ordinances.index');
    Route::post('/ordinances', [OrdinanceController::class, 'store'])->name('ordinances.store');
    Route::put('/ordinances/{id}', [OrdinanceController::class, 'update'])->name('ordinances.update');
    Route::delete('/ordinances/{id}', [OrdinanceController::class, 'destroy'])->name('ordinances.destroy');




    //Requests | Certificates
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::put('/requests/{id}/status', [RequestController::class, 'updateStatus'])
        ->name('requests.updateStatus');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/fetch', [RequestController::class, 'fetch'])
        ->name('requests.fetch');
    Route::delete('/requests/{id}', [RequestController::class, 'destroy'])
        ->name('requests.destroy');

    // Concerns
    Route::get('/concerns', [ConcernController::class, 'index'])->name('concerns.index');
    Route::put('/concerns/{id}/status', [ConcernController::class, 'updateStatus'])
        ->name('concerns.updateStatus');
    Route::get('/concerns/fetch', [ConcernController::class, 'fetch'])
        ->name('concern.fetch');
    Route::delete('/concerns/{id}', [ConcernController::class, 'destroy'])
        ->name('concern.destroy');

    // Blotters
    Route::get('/blotters', [BlotterController::class, 'index'])->name('blotters.index');
    Route::put('/blotter/{id}/status', [BlotterController::class, 'updateStatus'])
        ->name('blotters.updateStatus');
    Route::get('/blotter/fetch', [BlotterController::class, 'fetch'])
        ->name('blotters.fetch');
    Route::delete('/blotters/{id}', [BlotterController::class, 'destroy'])
        ->name('blotters.destroy');
    Route::post('/blotter/store', [BlotterController::class, 'store'])
        ->name('blotters.store');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::post('/news/store', [NewsController::class, 'store'])
        ->name('news.store');
    Route::get('/news/fetch', [NewsController::class, 'fetch'])
        ->name('news.fetch');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])
        ->name('news.destroy');


    //admin notification routes

    Route::get('/notifications/counts', [NotificationController::class, 'counts']);





    // Admin route to view all residents
    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/fetch', [ResidentController::class, 'fetch'])
        ->name('residents.fetch');
    Route::get('/residents/{id}', [ResidentController::class, 'show']);
    Route::get('/residents/edit/{id}', [ResidentController::class, 'editshow']);
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::post('/residents', [ResidentController::class, 'store'])->name('residents.store');
    Route::delete('/residents/{id}', [ResidentController::class, 'destroy']);
    Route::put('/residents/{id}', [ResidentController::class, 'update']);





    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
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
