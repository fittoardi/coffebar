<?php

namespace App\Services\Otp;

use App\Services\Otp\Contracts\SmsProviderInterface;
use App\Services\Otp\Providers\FonnteSmsProvider;
use App\Services\Otp\Providers\LogSmsProvider;
use App\Services\Otp\Providers\TwilioSmsProvider;
use InvalidArgumentException;

class SmsProviderManager
{
    public function driver(?string $driver = null): SmsProviderInterface
    {
        $driver = $driver ?? config('services.sms.provider', 'log');

        return match (strtolower($driver)) {
            'log' => new LogSmsProvider,
            'fonnte' => new FonnteSmsProvider,
            'twilio' => new TwilioSmsProvider,
            default => throw new InvalidArgumentException("Unsupported SMS driver [{$driver}]."),
        };
    }
}
