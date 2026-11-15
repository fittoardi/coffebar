<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomMenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Public Front-Facing Pages
Route::get('/', [HomeController::class, 'index'])->name('home');

// Menu Catalog & Detail
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{product:slug}', [MenuController::class, 'show'])->name('menu.show');

// Custom Menu Builder (Berkreasi)
Route::get('/berkreasi', [CustomMenuController::class, 'index'])->name('custom.index');
Route::post('/berkreasi/simpan', [CustomMenuController::class, 'store'])->name('custom.store');
Route::post('/berkreasi/pesan', [CustomMenuController::class, 'addToCart'])->name('custom.add-to-cart');

// Cart Routes
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/hapus/{rowId}', [CartController::class, 'remove'])->name('remove');
    Route::post('/kosongkan', [CartController::class, 'clear'])->name('clear');
});

// Checkout Routes (Requires Auth)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pesanan-saya', [OrderController::class, 'index'])->name('orders.index');
});

// Order Ticket & Receipt
Route::get('/pesanan/{orderCode}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/pesanan/{orderCode}/struk', [OrderController::class, 'receipt'])->name('orders.receipt');

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

// Protected Cashier Area (Phase 3/4)
Route::middleware(['auth', 'role:cashier,admin'])->prefix('kasir')->name('cashier.')->group(function () {
    Route::get('/', function () {
        return 'Halaman Dashboard Kasir (Akan diimplementasikan pada Fase Kasir)';
    })->name('dashboard');
});

// Protected Admin Area (Phase 4)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return 'Halaman Dashboard Admin (Akan diimplementasikan pada Fase Admin)';
    })->name('dashboard');
});
