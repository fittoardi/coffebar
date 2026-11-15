<?php

use App\Models\Category;
use App\Models\CustomMenu;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page renders successfully with popular products and categories', function () {
    $category = Category::factory()->create(['name' => 'Kopi']);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Espresso Classic',
        'is_available' => true,
    ]);

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('COFFEBAR');
    $response->assertSee('Kopi Terbaik');
    $response->assertSee('Espresso Classic');
});

test('menu page can filter products by category', function () {
    $catCoffee = Category::factory()->create(['name' => 'Kopi', 'slug' => 'kopi']);
    $catSnack = Category::factory()->create(['name' => 'Snack', 'slug' => 'snack']);

    $prodCoffee = Product::factory()->create([
        'category_id' => $catCoffee->id,
        'name' => 'Caramel Latte',
    ]);
    $prodSnack = Product::factory()->create([
        'category_id' => $catSnack->id,
        'name' => 'French Fries',
    ]);

    $response = $this->get('/menu?kategori=kopi');
    $response->assertStatus(200);
    $response->assertSee('Caramel Latte');
    $response->assertDontSee('French Fries');
});

test('menu page can search products by keyword', function () {
    $category = Category::factory()->create();
    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Matcha Fusion Latte',
    ]);
    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Butter Croissant',
    ]);

    $response = $this->get('/menu?q=Matcha');
    $response->assertStatus(200);
    $response->assertSee('Matcha Fusion Latte');
    $response->assertDontSee('Butter Croissant');
});

test('custom menu builder page renders successfully', function () {
    ProductOption::factory()->create(['type' => 'base', 'name' => 'Espresso Blend']);
    ProductOption::factory()->create(['type' => 'size', 'name' => 'Medium (M)']);

    $response = $this->get('/berkreasi');
    $response->assertStatus(200);
    $response->assertSee('Racik Minumanmu Sendiri');
    $response->assertSee('Espresso Blend');
});

test('guest is redirected to login when trying to order custom menu', function () {
    $response = $this->post('/berkreasi/pesan', [
        'name' => 'Minuman Rahasia',
        'base' => 'Espresso Blend',
        'size' => 'Medium (M)',
        'sugar_level' => '50%',
        'ice_level' => 'Dingin',
        'price' => 25000,
    ]);

    $response->assertRedirect(route('login'));
});

test('authenticated user can save custom menu and add to cart', function () {
    $user = User::factory()->customer()->create();

    // 1. Save custom menu to favorites
    $responseSave = $this->actingAs($user)->post('/berkreasi/simpan', [
        'name' => 'Kopi Senja Spesial',
        'base' => 'Espresso Blend',
        'size' => 'Large (L)',
        'sugar_level' => '50%',
        'ice_level' => 'Dingin',
        'options' => ['Boba Brown Sugar', 'Whipped Cream'],
        'price' => 35000,
    ]);

    $responseSave->assertRedirect(route('custom.index'));
    expect(CustomMenu::where('user_id', $user->id)->where('name', 'Kopi Senja Spesial')->exists())->toBeTrue();

    // 2. Add custom drink directly to cart
    $responseCart = $this->actingAs($user)->post('/berkreasi/pesan', [
        'name' => 'Kopi Senja Spesial',
        'base' => 'Espresso Blend',
        'size' => 'Large (L)',
        'sugar_level' => '50%',
        'ice_level' => 'Dingin',
        'options' => ['Boba Brown Sugar'],
        'price' => 30000,
        'quantity' => 2,
    ]);

    $responseCart->assertRedirect(route('cart.index'));

    $cartService = app(CartService::class);
    expect($cartService->getCount())->toBe(2);
    expect($cartService->getTotal())->toBe(60000.0);
});
