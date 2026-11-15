<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Cashier\CashierDashboardController;
use App\Http\Controllers\Cashier\CashierOrderController;
use App\Http\Controllers\Cashier\CashierPosController;
use App\Http\Controllers\Cashier\CashierShiftController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomMenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentWebhookController;
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

// Midtrans Payment Webhook
Route::post('/webhook/midtrans', [PaymentWebhookController::class, 'handle'])->name('webhook.midtrans');

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

// Protected Cashier Area (Phase 3)
Route::middleware(['auth', 'role:cashier,admin'])->prefix('kasir')->name('cashier.')->group(function () {
    // Real-time Dashboard & live feed
    Route::get('/', [CashierDashboardController::class, 'index'])->name('dashboard');
    Route::get('/feed', [CashierDashboardController::class, 'feed'])->name('feed');

    // Order Verification & QR Scan
    Route::get('/verifikasi', [CashierOrderController::class, 'verify'])->name('verify');
    Route::post('/pesanan/{order}/status', [CashierOrderController::class, 'updateStatus'])->name('order.update-status');
    Route::post('/pesanan/{order}/bayar', [CashierOrderController::class, 'processPayment'])->name('order.pay');
    Route::post('/produk/{product}/toggle', [CashierOrderController::class, 'toggleProductAvailability'])->name('product.toggle');

    // Walk-in Point of Sale
    Route::get('/pos', [CashierPosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [CashierPosController::class, 'store'])->name('pos.store');

    // Cashier Shifts
    Route::get('/shift', [CashierShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shift/buka', [CashierShiftController::class, 'start'])->name('shifts.start');
    Route::post('/shift/{shift}/tutup', [CashierShiftController::class, 'close'])->name('shifts.close');
});

// Protected Admin Area (Phase 4)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return 'Halaman Dashboard Admin (Akan diimplementasikan pada Fase Admin)';
    })->name('dashboard');
});
