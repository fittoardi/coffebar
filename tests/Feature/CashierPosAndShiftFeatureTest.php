<?php

use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cashier can open a new shift with starting cash', function () {
    $cashier = User::factory()->cashier()->create();

    $response = $this->actingAs($cashier)->post('/kasir/shift/buka', [
        'starting_cash' => 250000,
        'notes' => 'Shift Pagi',
    ]);

    $response->assertRedirect(route('cashier.dashboard'));

    $shift = CashierShift::where('cashier_id', $cashier->id)->first();
    expect($shift)->not->toBeNull();
    expect($shift->status)->toBe('open');
    expect((float) $shift->starting_cash)->toBe(250000.0);
    expect((float) $shift->total_cash_expected)->toBe(250000.0);
});

test('cashier can process walk-in POS order and it updates active shift balance', function () {
    $cashier = User::factory()->cashier()->create();

    // Open active shift
    $shift = CashierShift::create([
        'cashier_id' => $cashier->id,
        'started_at' => now(),
        'starting_cash' => 200000,
        'total_cash_expected' => 200000,
        'total_sales' => 0,
        'status' => 'open',
    ]);

    $product = Product::factory()->create([
        'name' => 'Caramel Latte',
        'price' => 28000,
    ]);

    $response = $this->actingAs($cashier)->post('/kasir/pos', [
        'customer_name' => 'Meja 5',
        'payment_method' => 'cash',
        'cash_given' => 30000,
        'items' => [
            [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => 28000,
                'quantity' => 1,
            ],
        ],
    ]);

    $order = Order::where('source', 'cashier')->first();
    expect($order)->not->toBeNull();
    expect($order->customer_name)->toBe('Meja 5');
    expect((float) $order->total_amount)->toBe(28000.0);
    expect($order->payment_status)->toBe('paid');
    expect($order->cashier_id)->toBe($cashier->id);

    // Assert shift was incremented with the cash order
    $freshShift = $shift->fresh();
    expect((float) $freshShift->total_sales)->toBe(28000.0);
    expect((float) $freshShift->total_cash_expected)->toBe(228000.0); // 200k starting + 28k sale
});

test('cashier can close shift with physical cash count and calculates variance', function () {
    $cashier = User::factory()->cashier()->create();

    $shift = CashierShift::create([
        'cashier_id' => $cashier->id,
        'started_at' => now(),
        'starting_cash' => 200000,
        'total_cash_expected' => 250000,
        'total_sales' => 50000,
        'status' => 'open',
    ]);

    // Close with exactly expected cash (balanced)
    $response = $this->actingAs($cashier)->post('/kasir/shift/'.$shift->id.'/tutup', [
        'total_cash_actual' => 250000,
        'notes' => 'Kas seimbang',
    ]);

    $response->assertRedirect(route('cashier.shifts.index'));

    $freshShift = $shift->fresh();
    expect($freshShift->status)->toBe('closed');
    expect($freshShift->ended_at)->not->toBeNull();
    expect((float) $freshShift->total_cash_actual)->toBe(250000.0);
});
