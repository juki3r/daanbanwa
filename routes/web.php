<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrdinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
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

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::put('/requests/{id}/status', [RequestController::class, 'updateStatus'])
        ->name('requests.updateStatus');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

    Route::delete('/requests/{id}', [RequestController::class, 'destroy'])
        ->name('requests.destroy');

    Route::get('/concern', [ConcernController::class, 'index'])->name('concern.index');
    Route::put('/concern/{id}/status', [ConcernController::class, 'updateStatus'])
        ->name('concern.updateStatus');

    Route::delete('/concern/{id}', [ConcernController::class, 'destroy'])
        ->name('concern.destroy');


    Route::get('/blotter', [BlotterController::class, 'index'])->name('blotter.index');
    Route::put('/blotter/{id}/status', [BlotterController::class, 'updateStatus'])
        ->name('blotter.updateStatus');
    Route::delete('/blotter/{id}', [ConcernController::class, 'destroy'])
        ->name('blotter.destroy');






    Route::get('/residents', [AdminController::class, 'index'])->name('residents.index');
    Route::get('/concerns', [AdminController::class, 'index'])->name('concerns.index');
    Route::get('/blotter', [AdminController::class, 'index'])->name('blotters.index');
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::get('/news', [AdminController::class, 'index'])->name('news.index');
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
