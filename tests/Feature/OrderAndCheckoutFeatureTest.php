<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('checkout requires authentication', function () {
    $response = $this->get('/checkout');
    $response->assertRedirect(route('login'));
});

test('checkout redirects back to cart if cart is empty', function () {
    $user = User::factory()->customer()->create();

    $response = $this->actingAs($user)->get('/checkout');
    $response->assertRedirect(route('cart.index'));
});

test('user can checkout and create order with cash payment for cashier pickup', function () {
    $user = User::factory()->customer()->create([
        'name' => 'Budi Santoso',
        'phone' => '08123456789',
    ]);

    $product = Product::factory()->create([
        'name' => 'Cafe Latte',
        'price' => 26000,
    ]);

    // Populate cart
    $cartService = app(CartService::class);
    $cartService->add([
        'product_id' => $product->id,
        'name' => $product->name,
        'price' => 26000,
        'quantity' => 2,
        'options' => ['size' => 'Medium (M)'],
        'notes' => 'Less ice',
    ]);

    $response = $this->actingAs($user)->post('/checkout', [
        'payment_method' => 'cash',
        'notes' => 'Tolong sedotan kertas',
    ]);

    $order = Order::where('user_id', $user->id)->first();
    expect($order)->not->toBeNull();
    expect($order->status)->toBe('menunggu');
    expect($order->payment_method)->toBe('cash');
    expect($order->total_amount)->toBe('52000.00');
    expect($order->order_code)->toStartWith('CF-');
    expect($order->items()->count())->toBe(1);

    // Cart should be empty now
    expect($cartService->isEmpty())->toBeTrue();

    // Redirected to order ticket show page
    $response->assertRedirect(route('orders.show', $order->order_code));
});

test('order show page displays order code and QR code', function () {
    $user = User::factory()->customer()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'order_code' => 'CF-20417',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($user)->get('/pesanan/CF-20417');
    $response->assertStatus(200);
    $response->assertSee('CF-20417');
    $response->assertSee('svg'); // QR code svg is rendered
});

test('customer can see their order history in pesanan saya', function () {
    $user = User::factory()->customer()->create();
    $order1 = Order::factory()->create([
        'user_id' => $user->id,
        'order_code' => 'CF-11111',
    ]);
    $order2 = Order::factory()->create([
        'user_id' => $user->id,
        'order_code' => 'CF-22222',
    ]);

    $response = $this->actingAs($user)->get('/pesanan-saya');
    $response->assertStatus(200);
    $response->assertSee('CF-11111');
    $response->assertSee('CF-22222');
});

test('customer cannot view other customer order ticket', function () {
    $userA = User::factory()->customer()->create();
    $userB = User::factory()->customer()->create();

    $orderA = Order::factory()->create([
        'user_id' => $userA->id,
        'order_code' => 'CF-99999',
    ]);

    $response = $this->actingAs($userB)->get('/pesanan/CF-99999');
    $response->assertStatus(403);
});

test('digital receipt page renders correctly for printing', function () {
    $user = User::factory()->customer()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'order_code' => 'CF-88888',
        'customer_name' => 'John Doe',
        'total_amount' => 50000,
    ]);

    $response = $this->actingAs($user)->get('/pesanan/CF-88888/struk');
    $response->assertStatus(200);
    $response->assertSee('CF-88888');
    $response->assertSee('COFFEBAR');
    $response->assertSee('John Doe');
});
