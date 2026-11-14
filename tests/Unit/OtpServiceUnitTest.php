<?php

use App\Exceptions\OtpInvalidException;
use App\Exceptions\OtpRateLimitException;
use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Services\Otp\Contracts\SmsProviderInterface;
use App\Services\Otp\OtpService;
use App\Services\Otp\SmsProviderManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    $this->smsMock = Mockery::mock(SmsProviderInterface::class);
    $this->smsMock->shouldReceive('send')->andReturn(true)->byDefault();

    $manager = Mockery::mock(SmsProviderManager::class);
    $manager->shouldReceive('driver')->andReturn($this->smsMock);

    $this->otpService = new OtpService($manager);
});

test('it generates a 6 digit otp code, stores it hashed, and dispatches email', function () {
    $result = $this->otpService->generate('user@coffebar.id', 'email');

    expect($result)->toHaveKeys(['otp', 'plain_code']);
    expect(strlen($result['plain_code']))->toBe(6);
    expect(is_numeric($result['plain_code']))->toBeTrue();

    $otp = OtpCode::where('identifier', 'user@coffebar.id')->first();
    expect($otp)->not->toBeNull();
    expect($otp->code_hash)->not->toBe($result['plain_code']);
    expect(Hash::check($result['plain_code'], $otp->code_hash))->toBeTrue();
    expect($otp->attempts)->toBe(0);
    expect($otp->expires_at->isFuture())->toBeTrue();

    Mail::assertSent(SendOtpMail::class, function ($mail) use ($result) {
        return $mail->hasTo('user@coffebar.id') && $mail->otpCode === $result['plain_code'];
    });
});

test('it verifies correct otp code successfully', function () {
    $result = $this->otpService->generate('user@coffebar.id', 'email');

    $verified = $this->otpService->verify('user@coffebar.id', $result['plain_code']);
    expect($verified)->toBeTrue();

    $otp = OtpCode::where('identifier', 'user@coffebar.id')->first();
    expect($otp->verified_at)->not->toBeNull();
});

test('it rejects expired otp code', function () {
    $result = $this->otpService->generate('user@coffebar.id', 'email');

    // Fast-forward time past expiration
    $this->travel(6)->minutes();

    expect(fn () => $this->otpService->verify('user@coffebar.id', $result['plain_code']))
        ->toThrow(OtpInvalidException::class, 'kedaluwarsa');
});

test('it increments attempts on wrong code and enforces max attempts limit', function () {
    $result = $this->otpService->generate('user@coffebar.id', 'email');

    // Attempt 1: wrong code
    try {
        $this->otpService->verify('user@coffebar.id', '000000');
    } catch (OtpInvalidException $e) {
        expect($e->attemptsLeft)->toBe(2);
    }

    $otp = OtpCode::where('identifier', 'user@coffebar.id')->first();
    expect($otp->attempts)->toBe(1);

    // Attempt 2: wrong code
    try {
        $this->otpService->verify('user@coffebar.id', '111111');
    } catch (OtpInvalidException $e) {
        expect($e->attemptsLeft)->toBe(1);
    }

    $otp->refresh();
    expect($otp->attempts)->toBe(2);

    // Attempt 3: wrong code -> limit reached
    expect(fn () => $this->otpService->verify('user@coffebar.id', '222222'))
        ->toThrow(OtpInvalidException::class, 'Batas maksimal percobaan');

    $otp->refresh();
    expect($otp->attempts)->toBe(3);

    // Subsequent attempt with correct code is now blocked because max attempts was exceeded
    expect(fn () => $this->otpService->verify('user@coffebar.id', $result['plain_code']))
        ->toThrow(OtpInvalidException::class, 'Batas percobaan');
});

test('it enforces rate limiting of maximum 3 requests per 10 minutes', function () {
    $identifier = 'customer@coffebar.id';

    // 1st request -> ok
    $this->otpService->generate($identifier, 'email');
    // 2nd request -> ok
    $this->otpService->generate($identifier, 'email');
    // 3rd request -> ok
    $this->otpService->generate($identifier, 'email');

    // 4th request within 10 minutes -> should fail with rate limit exception
    expect(fn () => $this->otpService->generate($identifier, 'email'))
        ->toThrow(OtpRateLimitException::class, 'batas maksimal');

    // Travel 11 minutes into the future -> rate limit window resets
    $this->travel(11)->minutes();

    // Now it should succeed again
    $newResult = $this->otpService->generate($identifier, 'email');
    expect($newResult)->toHaveKey('plain_code');
});
