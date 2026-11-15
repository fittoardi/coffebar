<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cashier dashboard renders with active metrics and orders', function () {
    $cashier = User::factory()->cashier()->create();

    $order = Order::factory()->create([
        'order_code' => 'CF-12345',
        'status' => 'menunggu',
        'payment_method' => 'cash',
    ]);

    $response = $this->actingAs($cashier)->get('/kasir');
    $response->assertStatus(200);
    $response->assertSee('Dashboard Pesanan Kasir');
    $response->assertSee('CF-12345');
});

test('cashier can search and verify order by code', function () {
    $cashier = User::factory()->cashier()->create();

    $order = Order::factory()->create([
        'order_code' => 'CF-99887',
        'status' => 'menunggu',
        'total_amount' => 50000,
    ]);

    $response = $this->actingAs($cashier)->get('/kasir/verifikasi?code=CF-99887');
    $response->assertStatus(200);
    $response->assertSee('CF-99887');
    $response->assertSee('Verifikasi Kode & QR Pesanan');
});

test('cashier can update order status workflow', function () {
    $cashier = User::factory()->cashier()->create();

    $order = Order::factory()->create([
        'status' => 'diterima',
    ]);

    // 1. Advance to diproses
    $this->actingAs($cashier)
        ->post('/kasir/pesanan/'.$order->id.'/status', ['status' => 'diproses'])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe('diproses');

    // 2. Advance to siap
    $this->actingAs($cashier)
        ->post('/kasir/pesanan/'.$order->id.'/status', ['status' => 'siap'])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe('siap');

    // 3. Complete order (selesai)
    $this->actingAs($cashier)
        ->post('/kasir/pesanan/'.$order->id.'/status', ['status' => 'selesai'])
        ->assertRedirect();

    $freshOrder = $order->fresh();
    expect($freshOrder->status)->toBe('selesai');
    expect($freshOrder->completed_at)->not->toBeNull();
});

test('cashier can process counter payment with cash and calculates change', function () {
    $cashier = User::factory()->cashier()->create();

    $order = Order::factory()->create([
        'order_code' => 'CF-77777',
        'status' => 'menunggu',
        'payment_method' => 'cash',
        'payment_status' => 'pending',
        'total_amount' => 45000,
    ]);

    $response = $this->actingAs($cashier)->post('/kasir/pesanan/'.$order->id.'/bayar', [
        'payment_method' => 'cash',
        'cash_given' => 50000,
    ]);

    $response->assertRedirect(route('orders.receipt', 'CF-77777'));

    $freshOrder = $order->fresh();
    expect($freshOrder->status)->toBe('diterima');
    expect($freshOrder->payment_status)->toBe('paid');
    expect($freshOrder->cashier_id)->toBe($cashier->id);

    $payment = $freshOrder->payment;
    expect($payment)->not->toBeNull();
    expect((float) $payment->amount)->toBe(45000.0);
    expect((float) $payment->cash_given)->toBe(50000.0);
    expect((float) $payment->change_given)->toBe(5000.0);
});

test('cashier can toggle product availability', function () {
    $cashier = User::factory()->cashier()->create();
    $product = Product::factory()->create(['is_available' => true]);

    $this->actingAs($cashier)
        ->post('/kasir/produk/'.$product->id.'/toggle')
        ->assertRedirect();

    expect($product->fresh()->is_available)->toBeFalse();

    $this->actingAs($cashier)
        ->post('/kasir/produk/'.$product->id.'/toggle')
        ->assertRedirect();

    expect($product->fresh()->is_available)->toBeTrue();
});
