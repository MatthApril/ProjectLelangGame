<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('do-login');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register User
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('do-register');

// Open Shop
Route::get('/open-shop', [AuthController::class, 'showOpenShop'])->name('open-shop');
Route::post('/open-shop', [AuthController::class, 'doOpenShop'])->name('do-open-shop');

// Konfirmasi Ganti Profile
Route::group(['middleware' => ['auth', 'check_role:user,seller,admin', 'check_status']], function() {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/change-username', [AuthController::class, 'changeUsername'])->name('change-username');
    Route::post('/change-shop-name', [AuthController::class, 'changeShopName'])->name('change-shop-name');
});

// Ganti Password
Route::group(['middleware' => ['auth', 'check_role:user,seller', 'check_status', 'check_change_pwd_status']], function() {
    Route::get('/change-pwd', [AuthController::class, 'showChangePassword'])->name('change-pwd-view');
    Route::post('/change-pwd', [VerificationController::class, 'changePassword'])->name('change-pwd');
});

// Verifikasi ganti Email
Route::group(['middleware' => ['auth', 'check_role:user,seller']], function() {
    Route::post('/change-email/verify', [VerificationController::class, 'store'])->name('change-email.store');
    Route::get('/change-email/verify/{unique_id}', [VerificationController::class, 'show'])->name('change-email.verify.uid');
    Route::put('/change-email/verify/{unique_id}', [VerificationController::class, 'update'])->name('change-email.verify.update-uid');
});

// Konfirmasi ganti Password
Route::group(['middleware' => ['auth', 'check_role:user,seller']], function() {
    Route::post('/change-pwd/verify', [VerificationController::class, 'store'])->name('change-pwd.store');
    Route::get('/change-pwd/verify/{unique_id}', [VerificationController::class, 'show'])->name('change-pwd.verify.uid');
    Route::put('/change-pwd/verify/{unique_id}', [VerificationController::class, 'update'])->name('change-pwd.verify.update-uid');
});

// Forgot Password
Route::get('/forgot-pwd-email', [VerificationController::class, 'showForgotPwdEmailForm'])->name('forgot-pwd-email-view');
Route::post('/forgot-pwd/verify', [VerificationController::class, 'sendResetPwdOtp'])->name('forgot-pwd.store');
Route::post('/forgot-pwd/resend', [VerificationController::class, 'resendOTP'])->name('forgot-pwd.resend');

Route::group(['middleware' => ['check_forgot_pwd_status']], function() {
    Route::get('/forgot-pwd/update', [VerificationController::class, 'showForgotPwdForm'])->name('forgot-pwd-view');
    Route::post('/forgot-pwd/update', [VerificationController::class, 'changeForgotPassword'])->name('forgot-pwd.update');
});

Route::get('/forgot-pwd/verify/{unique_id}', [VerificationController::class, 'forgotPwdShow'])->name('forgot-pwd.verify.uid');
Route::put('/forgot-pwd/verify/{unique_id}', [VerificationController::class, 'forgotPwdUpdate'])->name('forgot-pwd.verify.update-uid');

// Verify Registrasi
Route::group(['middleware' => ['auth', 'check_role:user,seller']], function() {
    Route::get('/verify', [VerificationController::class, 'index'])->name('verify.index');
    Route::post('/verify', [VerificationController::class, 'store'])->name('verify.store');
    Route::get('/verify/{unique_id}', [VerificationController::class, 'show'])->name('verify.uid');
    Route::put('/verify/{unique_id}', [VerificationController::class, 'update'])->name('verify.update-uid');
});
