<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('doRegister');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth', 'check_role:user,seller,admin', 'check_status']], function() {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::get('/change-pwd', [AuthController::class, 'showChangePassword'])->name('change-pwd-view');
    Route::post('/change-pwd', [AuthController::class, 'showChangePassword'])->name('change-pwd-view');
    Route::get('/change-email', [AuthController::class, 'showChangeEmail'])->name('change-email-view');
    Route::post('/change-email', [AuthController::class, 'showChangeEmail'])->name('change-email-view');
});

Route::group(['middleware' => ['auth', 'check_role:user,seller']], function() {
    Route::get('/verify', [VerificationController::class, 'index'])->name('verify.index');
    Route::post('/verify', [VerificationController::class, 'store'])->name('verify.store');
    Route::post('/changeEmail', [VerificationController::class, 'store'])->name('verify.changeEmail');
    Route::post('/changePwd', [VerificationController::class, 'store'])->name('verify.changePwd');
    Route::get('/verify/{unique_id}', [VerificationController::class, 'show'])->name('verify.uid');
    Route::put('/verify/{unique_id}', [VerificationController::class, 'update'])->name('verify.update-uid');
});
