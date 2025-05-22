<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;

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
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('appointments/book', [AppointmentController::class, 'book'])->name('appointments.book');
Route::get('doctor/{id}/slots', [AppointmentController::class, 'getSlots'])->name('appointments.slots');
});



