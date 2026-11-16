<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view staff list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Staff Budi', 'role' => 'cashier']);

    $response = $this->actingAs($admin)->get(route('admin.staff.index'));
    $response->assertOk();
    $response->assertSee('Staff Budi');
});

test('admin can create new cashier staff with direct verification', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.staff.store'), [
        'name' => 'Barista Doni',
        'email' => 'doni@coffebar.id',
        'phone' => '089988776655',
        'role' => 'cashier',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect(route('admin.staff.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'email' => 'doni@coffebar.id',
        'role' => 'cashier',
    ]);

    $doni = User::where('email', 'doni@coffebar.id')->first();
    expect($doni->email_verified_at)->not->toBeNull();
    expect($doni->phone_verified_at)->not->toBeNull();
});

test('admin can update staff details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['role' => 'cashier', 'name' => 'Old Name']);

    $response = $this->actingAs($admin)->put(route('admin.staff.update', $staff), [
        'name' => 'Updated Barista Name',
        'email' => $staff->email,
        'phone' => $staff->phone,
        'role' => 'cashier',
    ]);

    $response->assertRedirect(route('admin.staff.index'));
    $this->assertDatabaseHas('users', [
        'id' => $staff->id,
        'name' => 'Updated Barista Name',
    ]);
});

test('admin cannot delete their own account', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete(route('admin.staff.destroy', $admin));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin can delete other staff member', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $response = $this->actingAs($admin)->delete(route('admin.staff.destroy', $cashier));

    $response->assertRedirect(route('admin.staff.index'));
    $this->assertDatabaseMissing('users', ['id' => $cashier->id]);
});

test('admin can view sales report and analytics', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $order = Order::factory()->create([
        'source' => 'online',
        'status' => 'completed',
        'total_amount' => 45000,
    ]);

    Payment::factory()->create([
        'order_id' => $order->id,
        'status' => 'paid',
        'method' => 'qris',
        'amount' => 45000,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.index', ['range' => 'today']));
    $response->assertOk();
    $response->assertSee('Laporan Penjualan');
    $response->assertSee('45.000');
});

test('admin can export sales report as csv stream', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $order = Order::factory()->create([
        'order_code' => 'CB-TEST-REPORT-CSV',
        'source' => 'cashier',
        'status' => 'completed',
        'total_amount' => 30000,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.export', ['range' => 'today']));
    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

    // Retrieve stream content
    ob_start();
    $response->sendContent();
    $content = ob_get_clean();

    expect($content)->toContain('CB-TEST-REPORT-CSV');
    expect($content)->toContain('Kasir (Walk-in)');
});

test('admin can view and update company profile settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.settings.index'));
    $response->assertOk();
    $response->assertSee('Pengaturan Profil Kedai');

    $updateResponse = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'shop_name' => 'CoffeBar Artisan Senopati',
        'shop_tagline' => 'Kedai Kopi Pilihan Penikmat Sejati',
        'hero_title' => 'Cita Rasa Juara Dari Bumi Nusantara',
        'address' => 'Jl. Senopati No. 88, Jakarta Selatan',
        'contact_phone' => '081122334455',
        'opening_hours' => '07.00 - 23.00 WIB',
        'maps_url' => 'https://maps.google.com/?q=coffebar',
    ]);

    $updateResponse->assertRedirect(route('admin.settings.index'));
    $updateResponse->assertSessionHas('success');

    // Verify on public home page
    $homeResponse = $this->get(route('home'));
    $homeResponse->assertOk();
    $homeResponse->assertSee('Cita Rasa Juara Dari Bumi Nusantara');
    $homeResponse->assertSee('Jl. Senopati No. 88, Jakarta Selatan');
});
