<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register User
Route::get('/user-register', [AuthController::class, 'showUserRegisterForm'])->name('user-register');
Route::post('/user-register', [AuthController::class, 'doUserRegister'])->name('doRegister');

// Register Seller
Route::get('/seller-register', [AuthController::class, 'showSellerRegisterForm'])->name('seller-register');
Route::post('/seller-register', [AuthController::class, 'doSellerRegister'])->name('doRegister');

// Konfirmasi Ganti Profile
Route::group(['middleware' => ['auth', 'check_role:user,seller,admin', 'check_status']], function() {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::get('/change-pwd', [AuthController::class, 'showChangePassword'])->name('change-pwd-view');
    Route::post('/change-pwd', [AuthController::class, 'showChangePassword'])->name('change-pwd-view');
    Route::get('/change-email', [AuthController::class, 'showChangeEmail'])->name('change-email-view');
    Route::post('/change-email', [AuthController::class, 'showChangeEmail'])->name('change-email-view');
});

// Verify Registrasi
Route::group(['middleware' => ['auth', 'check_role:user,seller']], function() {
    Route::get('/verify', [VerificationController::class, 'index'])->name('verify.index');
    Route::post('/verify', [VerificationController::class, 'store'])->name('verify.store');
    Route::post('/changeEmail', [VerificationController::class, 'store'])->name('verify.changeEmail');
    Route::post('/changePwd', [VerificationController::class, 'store'])->name('verify.changePwd');
    Route::get('/verify/{unique_id}', [VerificationController::class, 'show'])->name('verify.uid');
    Route::put('/verify/{unique_id}', [VerificationController::class, 'update'])->name('verify.update-uid');
});
