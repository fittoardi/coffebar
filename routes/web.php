<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication & OTP Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');

    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');

    Route::get('/verify-otp', 'showVerifyOtp')->name('otp.verify.notice');
    Route::post('/verify-otp', 'verifyOtp')->name('otp.verify');
    Route::post('/resend-otp', 'resendOtp')->name('otp.resend');

    Route::post('/logout', 'logout')->name('logout');
});

// Protected Cashier Area
Route::middleware(['auth', 'role:cashier,admin'])->prefix('kasir')->name('cashier.')->group(function () {
    Route::get('/', function () {
        return 'Halaman Dashboard Kasir (Akan diimplementasikan pada Fase Kasir)';
    })->name('dashboard');
});

// Protected Admin Area
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return 'Halaman Dashboard Admin (Akan diimplementasikan pada Fase Admin)';
    })->name('dashboard');
});
