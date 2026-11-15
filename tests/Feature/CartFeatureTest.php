<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can add regular product to cart', function () {
    $category = Category::factory()->create(['name' => 'Kopi']);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Caramel Latte',
        'price' => 28000,
    ]);

    $response = $this->post('/keranjang/tambah', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response->assertRedirect(route('cart.index'));

    $cartService = app(CartService::class);
    expect($cartService->getCount())->toBe(2);
    expect($cartService->getTotal())->toBe(56000.0);
});

test('user can add product with options and extra prices are calculated', function () {
    $category = Category::factory()->create(['name' => 'Kopi']);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Americano',
        'price' => 22000,
    ]);

    ProductOption::factory()->create([
        'type' => 'size',
        'name' => 'Large (L)',
        'price' => 8000,
    ]);

    ProductOption::factory()->create([
        'type' => 'topping',
        'name' => 'Extra Espresso Shot',
        'price' => 6000,
    ]);

    $response = $this->postJson('/keranjang/tambah', [
        'product_id' => $product->id,
        'quantity' => 1,
        'size' => 'Large (L)',
        'toppings' => ['Extra Espresso Shot'],
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['message', 'row_id', 'cart_count', 'cart_total']);

    $cartService = app(CartService::class);
    // 22000 (base) + 8000 (size L) + 6000 (extra shot) = 36000
    expect($cartService->getTotal())->toBe(36000.0);
});

test('user can update item quantity in cart', function () {
    $product = Product::factory()->create(['price' => 20000]);
    $cartService = app(CartService::class);

    $rowId = $cartService->add([
        'product_id' => $product->id,
        'name' => $product->name,
        'price' => 20000,
        'quantity' => 1,
    ]);

    $response = $this->post('/keranjang/update', [
        'row_id' => $rowId,
        'quantity' => 3,
    ]);

    $response->assertRedirect(route('cart.index'));
    expect($cartService->getCount())->toBe(3);
    expect($cartService->getTotal())->toBe(60000.0);
});

test('user can remove item from cart', function () {
    $product = Product::factory()->create(['price' => 20000]);
    $cartService = app(CartService::class);

    $rowId = $cartService->add([
        'product_id' => $product->id,
        'name' => $product->name,
        'price' => 20000,
        'quantity' => 1,
    ]);

    $response = $this->delete('/keranjang/hapus/' . $rowId);
    $response->assertRedirect(route('cart.index'));

    expect($cartService->isEmpty())->toBeTrue();
});

test('user can clear cart', function () {
    $cartService = app(CartService::class);
    $cartService->add([
        'name' => 'Kopi A',
        'price' => 20000,
        'quantity' => 1,
    ]);
    $cartService->add([
        'name' => 'Kopi B',
        'price' => 25000,
        'quantity' => 1,
    ]);

    $this->post('/keranjang/kosongkan')->assertRedirect(route('cart.index'));
    expect($cartService->isEmpty())->toBeTrue();
});
