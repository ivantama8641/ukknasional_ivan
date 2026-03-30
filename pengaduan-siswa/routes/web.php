<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Disable register route if admin is only allowed, but we allow siswa register
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'redirect'])->name('home');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('complaints', App\Http\Controllers\Admin\ComplaintController::class)->only(['index', 'show', 'update']);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'edit', 'show']);
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'edit', 'show']);
    });

    // Guru Routes
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'guru'])->name('dashboard');
        Route::resource('complaints', App\Http\Controllers\Guru\ComplaintController::class)->only(['index', 'show', 'update']);
        Route::post('complaints/{complaint}/response', [App\Http\Controllers\Guru\ComplaintController::class, 'storeResponse'])->name('complaints.response');
    });

    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'siswa'])->name('dashboard');
        Route::resource('complaints', App\Http\Controllers\Siswa\ComplaintController::class);
        Route::post('complaints/{complaint}/response', [App\Http\Controllers\Siswa\ComplaintController::class, 'storeResponse'])->name('complaints.response');
    });

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});
