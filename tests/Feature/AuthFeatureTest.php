<?php

use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

test('registration page can be rendered', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
    $response->assertSee('Pendaftaran Pelanggan');
});

test('registration validates unique email and phone', function () {
    User::factory()->create([
        'email' => 'existing@coffebar.id',
        'phone' => '081122334455',
    ]);

    $response = $this->post('/register', [
        'name' => 'Baru',
        'email' => 'existing@coffebar.id',
        'phone' => '081122334455',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertSessionHasErrors(['email', 'phone']);
});

test('registration validates password confirmation and minimum length', function () {
    $response = $this->post('/register', [
        'name' => 'Baru',
        'email' => 'test@coffebar.id',
        'phone' => '081122334455',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('registration creates user with password and redirects to verify otp', function () {
    $response = $this->post('/register', [
        'name' => 'Ahmad Pelanggan',
        'email' => 'ahmad@example.com',
        'phone' => '081299887766',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect(route('otp.verify.notice'));

    $user = User::where('email', 'ahmad@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('customer');
    expect(Hash::check('secret123', $user->password))->toBeTrue();

    // Asserts OTP was dispatched via email
    Mail::assertSent(SendOtpMail::class, function ($mail) {
        return $mail->hasTo('ahmad@example.com');
    });

    // Asserts OTP code was recorded in database
    expect(OtpCode::where('identifier', 'ahmad@example.com')->exists())->toBeTrue();
});

test('login page can be rendered', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
    $response->assertSee('Masuk ke Akun');
    $response->assertSee('Kata Sandi');
});

test('customer can login directly with password', function () {
    $user = User::factory()->create([
        'email' => 'customer@example.com',
        'phone' => '081233445566',
        'password' => Hash::make('password123'),
        'role' => 'customer',
    ]);

    $response = $this->post('/login', [
        'identifier' => 'customer@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/');
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

test('customer can login with phone and password', function () {
    $user = User::factory()->create([
        'email' => 'phoneuser@example.com',
        'phone' => '081233445566',
        'password' => Hash::make('password123'),
        'role' => 'customer',
    ]);

    $response = $this->post('/login', [
        'identifier' => '081233445566',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/');
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

test('customer cannot login with incorrect password', function () {
    User::factory()->create([
        'email' => 'customer@example.com',
        'password' => Hash::make('correct123'),
    ]);

    $response = $this->post('/login', [
        'identifier' => 'customer@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['identifier']);
    expect(Auth::check())->toBeFalse();
});

test('user can verify otp and becomes authenticated', function () {
    $user = User::factory()->create([
        'email' => 'verify@example.com',
    ]);

    // Create active OTP in database
    $plainCode = '654321';
    OtpCode::create([
        'user_id' => $user->id,
        'identifier' => $user->email,
        'channel' => 'email',
        'code_hash' => Hash::make($plainCode),
        'attempts' => 0,
        'max_attempts' => 3,
        'expires_at' => now()->addMinutes(5),
    ]);

    $response = $this->post('/verify-otp', [
        'identifier' => $user->email,
        'code' => $plainCode,
    ]);

    $response->assertRedirect('/');
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

test('staff can login directly with password', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'owner@coffebar.id',
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->post('/login', [
        'identifier' => 'owner@coffebar.id',
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/admin');
    expect(Auth::check())->toBeTrue();
    expect(Auth::user()->isAdmin())->toBeTrue();
});

test('customer cannot access cashier or admin area', function () {
    $customer = User::factory()->customer()->create();

    $this->actingAs($customer)
        ->get('/kasir')
        ->assertStatus(403);

    $this->actingAs($customer)
        ->get('/admin')
        ->assertStatus(403);
});

test('cashier can access cashier area but not admin area', function () {
    $cashier = User::factory()->cashier()->create();

    $this->actingAs($cashier)
        ->get('/kasir')
        ->assertStatus(200);

    $this->actingAs($cashier)
        ->get('/admin')
        ->assertStatus(403);
});

test('admin can access both cashier and admin areas', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/kasir')
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertStatus(200);
});

test('api authentication returns token on valid otp verification', function () {
    $user = User::factory()->create([
        'email' => 'apiuser@coffebar.id',
    ]);

    $plainCode = '888999';
    OtpCode::create([
        'user_id' => $user->id,
        'identifier' => $user->email,
        'channel' => 'email',
        'code_hash' => Hash::make($plainCode),
        'attempts' => 0,
        'max_attempts' => 3,
        'expires_at' => now()->addMinutes(5),
    ]);

    $response = $this->postJson('/api/auth/verify-otp', [
        'identifier' => $user->email,
        'code' => $plainCode,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['message', 'token', 'user']);
});

test('staff internal login page can be rendered separately from customer login', function () {
    $response = $this->get(route('staff.login'));
    $response->assertStatus(200);
    $response->assertSee('Portal Internal Staf');
    $response->assertSee('Masuk Portal Staf');
    $response->assertSee('Kata Sandi');
});

test('staff can login through dedicated internal portal and redirect to cashier', function () {
    $cashier = User::factory()->cashier()->create([
        'email' => 'kasir.internal@coffebar.id',
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->post(route('staff.login.post'), [
        'email' => 'kasir.internal@coffebar.id',
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/kasir');
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($cashier->id);
});

test('customer account cannot login through staff portal', function () {
    $customer = User::factory()->create([
        'email' => 'regular.user@example.com',
        'password' => Hash::make('secret123'),
        'role' => 'customer',
    ]);

    $response = $this->post(route('staff.login.post'), [
        'email' => 'regular.user@example.com',
        'password' => 'secret123',
    ]);

    $response->assertSessionHasErrors(['email']);
    expect(Auth::check())->toBeFalse();
});
