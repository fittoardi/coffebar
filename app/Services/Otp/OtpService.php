<?php

namespace App\Services\Otp;

use App\Exceptions\OtpInvalidException;
use App\Exceptions\OtpRateLimitException;
use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public const EXPIRY_MINUTES = 5;

    public const MAX_ATTEMPTS = 3;

    public const MAX_REQUESTS_WINDOW_MINUTES = 10;

    public const MAX_REQUESTS_PER_WINDOW = 3;

    public function __construct(
        protected SmsProviderManager $smsManager
    ) {}

    /**
     * Check rate limiting: max 3 requests per 10 minutes for this identifier.
     */
    public function checkRateLimit(string $identifier): void
    {
        $windowStart = now()->subMinutes(self::MAX_REQUESTS_WINDOW_MINUTES);

        $recentCount = OtpCode::where('identifier', $identifier)
            ->where('created_at', '>=', $windowStart)
            ->count();

        if ($recentCount >= self::MAX_REQUESTS_PER_WINDOW) {
            throw new OtpRateLimitException(
                'Permintaan OTP untuk '.$identifier.' telah mencapai batas maksimal (3 kali dalam 10 menit). Silakan coba lagi nanti.'
            );
        }
    }

    /**
     * Generate a new 6-digit OTP code, save hashed, and dispatch.
     *
     * @return array{otp: OtpCode, plain_code: string}
     */
    public function generate(string $identifier, string $channel = 'email', ?int $userId = null): array
    {
        $this->checkRateLimit($identifier);

        $plainCode = sprintf('%06d', random_int(0, 999999));
        $codeHash = Hash::make($plainCode);

        $otp = OtpCode::create([
            'user_id' => $userId,
            'identifier' => $identifier,
            'channel' => $channel,
            'code_hash' => $codeHash,
            'attempts' => 0,
            'max_attempts' => self::MAX_ATTEMPTS,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
            'verified_at' => null,
        ]);

        $this->dispatchNotification($identifier, $plainCode, $channel);

        return [
            'otp' => $otp,
            'plain_code' => $plainCode,
        ];
    }

    /**
     * Generate OTPs for both email and phone simultaneously (e.g. during user registration).
     *
     * @return array{email_code: string, phone_code: string}
     */
    public function generateForRegistration(string $email, string $phone, ?int $userId = null): array
    {
        $emailOtp = $this->generate($email, 'email', $userId);
        $phoneOtp = $this->generate($phone, 'whatsapp', $userId);

        return [
            'email_code' => $emailOtp['plain_code'],
            'phone_code' => $phoneOtp['plain_code'],
        ];
    }

    /**
     * Verify an OTP code.
     *
     * @throws OtpInvalidException
     */
    public function verify(string $identifier, string $plainCode, ?string $channel = null): bool
    {
        $query = OtpCode::where('identifier', $identifier)
            ->whereNull('verified_at')
            ->latest();

        if ($channel) {
            $query->where('channel', $channel);
        }

        $otp = $query->first();

        if (! $otp) {
            throw new OtpInvalidException(
                'Kode verifikasi tidak ditemukan atau sudah digunakan. Silakan minta kode baru.',
                'not_found'
            );
        }

        // Check if expired
        if ($otp->isExpired()) {
            throw new OtpInvalidException(
                'Kode verifikasi telah kedaluwarsa. Masa berlaku kode adalah '.self::EXPIRY_MINUTES.' menit.',
                'expired'
            );
        }

        // Check if max attempts reached
        if ($otp->hasExceededMaxAttempts()) {
            throw new OtpInvalidException(
                'Batas percobaan memasukkan kode telah terlampaui (maksimal '.self::MAX_ATTEMPTS.' kali). Silakan minta kode baru.',
                'max_attempts_exceeded'
            );
        }

        // Check OTP match
        if (! Hash::check($plainCode, $otp->code_hash)) {
            $otp->increment('attempts');
            $remaining = max(0, $otp->max_attempts - $otp->attempts);

            if ($remaining === 0) {
                throw new OtpInvalidException(
                    'Kode verifikasi salah. Batas maksimal percobaan ('.self::MAX_ATTEMPTS.' kali) telah habis. Silakan minta kode baru.',
                    'max_attempts_exceeded',
                    0
                );
            }

            throw new OtpInvalidException(
                "Kode verifikasi salah. Sisa percobaan: {$remaining} kali.",
                'invalid_code',
                $remaining
            );
        }

        // Mark verified
        $otp->update(['verified_at' => now()]);

        // If identifier belongs to a user, update verified timestamps
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            User::where('email', $identifier)->update(['email_verified_at' => now()]);
        } else {
            User::where('phone', $identifier)->update(['phone_verified_at' => now()]);
        }

        return true;
    }

    /**
     * Dispatch notification to user via the corresponding channel.
     */
    protected function dispatchNotification(string $identifier, string $plainCode, string $channel): void
    {
        if ($channel === 'email' || filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            Mail::to($identifier)->send(new SendOtpMail($plainCode, self::EXPIRY_MINUTES));
        } else {
            $message = "☕ CoffeBar: Kode verifikasi Anda adalah {$plainCode}. Berlaku selama ".self::EXPIRY_MINUTES.' menit. Jangan bagikan ke siapapun!';
            $this->smsManager->driver()->send($identifier, $message);
        }
    }
}
