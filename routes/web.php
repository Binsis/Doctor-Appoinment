<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\AppointmentController;

// Route::get('/', function () {
//     return view('welcome');
// });
// admin routes
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::prefix('admin/doctors')->group(function () {
        Route::any('/', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/create', [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/store', [DoctorController::class, 'store'])->name('doctors.store');
    });
});

// user routes
Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('appointments/book', [AppointmentController::class, 'book'])->name('appointments.book');
Route::get('doctor/{id}/slots', [AppointmentController::class, 'getSlots'])->name('appointments.slots');



