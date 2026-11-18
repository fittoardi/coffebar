<?php

use App\Models\CashierShift;
use App\Models\Category;
use App\Models\Order;
use App\Models\OtpCode;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('end-to-end: full journey from customer registration, custom menu order, to cashier payment and shift closing', function () {
    // 0. Setup menu data
    $category = Category::factory()->create(['name' => 'Kopi Pilihan']);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Espresso Base',
        'price' => 15000,
        'is_available' => true,
    ]);

    // 1. Customer registers
    $registerResponse = $this->post('/register', [
        'name' => 'Dewi Lestari',
        'email' => 'dewi@example.com',
        'phone' => '081299887766',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);
    $registerResponse->assertRedirect(route('otp.verify.notice'));

    $user = User::where('email', 'dewi@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('customer');

    // 2. Customer receives OTP and verifies
    $otpRecord = OtpCode::where('identifier', 'dewi@example.com')
        ->latest()
        ->first();
    expect($otpRecord)->not->toBeNull();

    $dummyCode = '123456';
    $otpRecord->update(['code_hash' => Hash::make($dummyCode)]);

    $verifyResponse = $this->post('/verify-otp', [
        'identifier' => 'dewi@example.com',
        'code' => $dummyCode,
    ]);
    $verifyResponse->assertRedirect('/');

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();

    // 3. Authenticated customer designs a custom coffee and adds to cart
    $customResponse = $this->actingAs($user)->post(route('custom.add-to-cart'), [
        'name' => 'Kopi Senja Spesial Dewi',
        'base' => 'Espresso Double Shot',
        'size' => 'Large (16oz)',
        'sugar_level' => 'Less Sugar (50%)',
        'ice_level' => 'Normal Ice',
        'options' => ['Sea Salt Foam', 'Aren Syrup'],
        'price' => 28000,
        'notes' => 'Tolong buat agak kental',
        'quantity' => 1,
    ]);
    $customResponse->assertRedirect(route('cart.index'));

    // Check cart contents
    $cartResponse = $this->actingAs($user)->get(route('cart.index'));
    $cartResponse->assertOk();
    $cartResponse->assertSee('Kopi Senja Spesial Dewi');

    // 4. Customer checks out with "cash" (bayar di kasir)
    $checkoutResponse = $this->actingAs($user)->post(route('checkout.process'), [
        'customer_name' => 'Dewi Lestari',
        'customer_phone' => '081299887766',
        'payment_method' => 'cash',
        'notes' => 'Antar ke meja nomor 7',
    ]);

    $order = Order::where('user_id', $user->id)->latest()->first();
    expect($order)->not->toBeNull();
    expect($order->status)->toBe('menunggu');
    $checkoutResponse->assertRedirect(route('orders.show', $order->order_code));

    // 5. Customer views Ticket with QR
    $ticketResponse = $this->actingAs($user)->get(route('orders.show', $order->order_code));
    $ticketResponse->assertOk();
    $ticketResponse->assertSee($order->order_code);

    // 6. Cashier logs in and opens shift
    $cashier = User::factory()->create([
        'name' => 'Rian Kasir',
        'email' => 'rian.kasir@coffebar.id',
        'role' => 'cashier',
    ]);

    $startShiftResponse = $this->actingAs($cashier)->post(route('cashier.shifts.start'), [
        'starting_cash' => 200000,
        'notes' => 'Uang pecahan kecil lengkap',
    ]);
    $startShiftResponse->assertRedirect(route('cashier.dashboard'));

    $activeShift = CashierShift::where('cashier_id', $cashier->id)
        ->where('status', 'open')
        ->first();
    expect($activeShift)->not->toBeNull();
    expect((float) $activeShift->starting_cash)->toBe(200000.0);

    // 7. Cashier verifies the customer order code via cashier verify page
    $verifySearchResponse = $this->actingAs($cashier)->get(route('cashier.verify', ['code' => $order->order_code]));
    $verifySearchResponse->assertOk();
    $verifySearchResponse->assertSee($order->order_code);

    // 8. Cashier processes cash payment at counter
    $orderTotal = (float) $order->total_amount;
    $cashGiven = $orderTotal + 10000; // Customer gives extra cash

    $paymentResponse = $this->actingAs($cashier)->post(route('cashier.order.pay', $order), [
        'payment_method' => 'cash',
        'cash_given' => $cashGiven,
    ]);
    $paymentResponse->assertRedirect(route('orders.receipt', $order->order_code));

    $order->refresh();
    expect($order->status)->toBe('diterima');
    expect($order->payment_status)->toBe('paid');
    expect($order->payment)->not->toBeNull();
    expect((float) $order->payment->change_given)->toBe(10000.0);

    // 9. Cashier updates status through the workflow: diterima -> diproses -> siap -> selesai
    $this->actingAs($cashier)->post(route('cashier.order.update-status', $order), ['status' => 'diproses'])
        ->assertRedirect();
    expect($order->fresh()->status)->toBe('diproses');

    $this->actingAs($cashier)->post(route('cashier.order.update-status', $order), ['status' => 'siap'])
        ->assertRedirect();
    expect($order->fresh()->status)->toBe('siap');

    $this->actingAs($cashier)->post(route('cashier.order.update-status', $order), ['status' => 'selesai'])
        ->assertRedirect();
    expect($order->fresh()->status)->toBe('selesai');
    expect($order->fresh()->completed_at)->not->toBeNull();

    // 10. Printable receipt check
    $receiptResponse = $this->actingAs($cashier)->get(route('orders.receipt', $order->order_code));
    $receiptResponse->assertOk();
    $receiptResponse->assertSee('Cetak Struk');
    $receiptResponse->assertSee($order->order_code);

    // 11. Cashier closes shift with physical cash count
    $expectedCash = 200000 + $orderTotal;
    $closeShiftResponse = $this->actingAs($cashier)->post(route('cashier.shifts.close', $activeShift), [
        'total_cash_actual' => $expectedCash,
        'notes' => 'Kas seimbang tidak ada selisih',
    ]);
    $closeShiftResponse->assertRedirect(route('cashier.shifts.index'));

    $activeShift->refresh();
    expect($activeShift->status)->toBe('closed');
    expect((float) $activeShift->total_cash_actual)->toBe($expectedCash);
});

test('security: role authorization matrix prevents unauthorized access across areas', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $cashier = User::factory()->create(['role' => 'cashier']);
    $admin = User::factory()->create(['role' => 'admin']);

    // 1. Guest cannot access cashier or admin
    $this->get(route('cashier.dashboard'))->assertRedirect(route('login'));
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

    // 2. Customer cannot access cashier or admin
    $this->actingAs($customer)->get(route('cashier.dashboard'))->assertForbidden();
    $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();

    // 3. Cashier can access cashier but CANNOT access admin
    $this->actingAs($cashier)->get(route('cashier.dashboard'))->assertOk();
    $this->actingAs($cashier)->get(route('admin.dashboard'))->assertForbidden();

    // 4. Admin can access both cashier and admin
    $this->actingAs($admin)->get(route('cashier.dashboard'))->assertOk();
    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
});

test('security: webhook endpoint is accessible without csrf restriction', function () {
    $order = Order::factory()->create([
        'order_code' => 'CB-WH-TEST',
        'status' => 'pending_payment',
        'total_amount' => 25000,
    ]);

    $serverKey = config('services.midtrans.server_key', 'dummy_server_key');
    $signature = hash('sha512', 'CB-WH-TEST20025000.00'.$serverKey);

    $response = $this->postJson(route('webhook.midtrans'), [
        'order_id' => 'CB-WH-TEST',
        'status_code' => '200',
        'gross_amount' => '25000.00',
        'transaction_status' => 'settlement',
        'signature_key' => $signature,
        'payment_type' => 'qris',
    ]);

    $response->assertOk();
    $response->assertJson(['message' => 'Notification processed successfully.']);
});
