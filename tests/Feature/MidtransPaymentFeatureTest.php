<?php

use App\Models\Order;
use App\Services\Payment\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('midtrans service creates snap token transaction', function () {
    $order = Order::factory()->create([
        'order_code' => 'CF-11223',
        'total_amount' => 50000,
    ]);

    $service = new MidtransService;
    $result = $service->createSnapTransaction($order);

    expect($result)->toHaveKeys(['token', 'redirect_url']);
    expect($result['token'])->not->toBeEmpty();
});

test('midtrans webhook updates order status to paid on settlement', function () {
    $order = Order::factory()->create([
        'order_code' => 'CF-55443',
        'status' => 'menunggu',
        'payment_status' => 'pending',
        'total_amount' => 60000,
    ]);

    $response = $this->postJson('/webhook/midtrans', [
        'order_id' => 'CF-55443',
        'status_code' => '200',
        'gross_amount' => '60000.00',
        'transaction_status' => 'settlement',
        'transaction_id' => 'TRX-MID-999',
    ]);

    $response->assertStatus(200);

    $freshOrder = $order->fresh();
    expect($freshOrder->status)->toBe('dibayar');
    expect($freshOrder->payment_status)->toBe('paid');
    expect($freshOrder->paid_at)->not->toBeNull();

    $payment = $freshOrder->payment;
    expect($payment->status)->toBe('paid');
    expect($payment->gateway_reference)->toBe('TRX-MID-999');
});

test('midtrans webhook cancels order on cancel or expire notification', function () {
    $order = Order::factory()->create([
        'order_code' => 'CF-99001',
        'status' => 'menunggu',
        'payment_status' => 'pending',
        'total_amount' => 30000,
    ]);

    $response = $this->postJson('/webhook/midtrans', [
        'order_id' => 'CF-99001',
        'status_code' => '200',
        'gross_amount' => '30000.00',
        'transaction_status' => 'expire',
    ]);

    $response->assertStatus(200);

    $freshOrder = $order->fresh();
    expect($freshOrder->status)->toBe('batal');
    expect($freshOrder->payment_status)->toBe('failed');
});
