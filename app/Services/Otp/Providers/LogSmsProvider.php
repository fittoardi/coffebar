<?php

namespace App\Services\Otp\Providers;

use App\Services\Otp\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Log;

class LogSmsProvider implements SmsProviderInterface
{
    public function send(string $phoneNumber, string $message): bool
    {
        Log::info(sprintf('[SMS/WhatsApp OTP Provider] To: %s | Message: %s', $phoneNumber, $message));

        return true;
    }
}
