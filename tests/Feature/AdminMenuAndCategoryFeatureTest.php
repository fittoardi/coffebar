<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access admin routes and is redirected to login', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('customer or cashier cannot access admin routes and receives forbidden 403', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();

    $cashier = User::factory()->create(['role' => 'cashier']);
    $this->actingAs($cashier)->get(route('admin.dashboard'))->assertForbidden();
});

test('admin can access dashboard and see overview', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertOk();
    $response->assertSee('Dashboard Administrasi');
});

test('admin can create a category with slug auto-generation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Kopi Manual Brew',
        'description' => 'Seduhan manual istimewa',
        'icon' => '☕',
        'sort_order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('categories', [
        'name' => 'Kopi Manual Brew',
        'slug' => 'kopi-manual-brew',
        'sort_order' => 1,
    ]);
});

test('admin can update a category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create(['name' => 'Old Category']);

    $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
        'name' => 'New Category Name',
        'description' => 'Updated description',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Category Name',
    ]);
});

test('admin cannot delete category if products are attached', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();
    Product::factory()->create(['category_id' => $category->id]);

    $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('admin can delete category if no products attached', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('admin can create product with custom options', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.products.store'), [
        'category_id' => $category->id,
        'name' => 'Caramel Macchiato Artisan',
        'price' => 28000,
        'description' => 'Kombinasi espresso, susu vanila dan saus karamel gurih',
        'is_available' => '1',
        'sort_order' => 2,
        'options' => [
            ['type' => 'size', 'name' => 'Regular', 'price' => 0, 'is_available' => 1],
            ['type' => 'size', 'name' => 'Large', 'price' => 5000, 'is_available' => 1],
            ['type' => 'addon', 'name' => 'Extra Shot', 'price' => 4000, 'is_available' => 1],
        ],
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('products', [
        'name' => 'Caramel Macchiato Artisan',
        'slug' => 'caramel-macchiato-artisan',
        'price' => 28000,
    ]);

    $product = Product::where('name', 'Caramel Macchiato Artisan')->first();
    expect($product->options)->toHaveCount(3);
});

test('admin can toggle product availability', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create(['is_available' => true]);

    $this->actingAs($admin)->patch(route('admin.products.toggle', $product))
        ->assertRedirect();

    expect($product->fresh()->is_available)->toBeFalse();

    $this->actingAs($admin)->patch(route('admin.products.toggle', $product))
        ->assertRedirect();

    expect($product->fresh()->is_available)->toBeTrue();
});

test('admin can delete product', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});
