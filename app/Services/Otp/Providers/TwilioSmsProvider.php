<?php

namespace App\Services\Otp\Providers;

use App\Services\Otp\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Log;

class TwilioSmsProvider implements SmsProviderInterface
{
    public function __construct(
        protected ?string $sid = null,
        protected ?string $token = null,
        protected ?string $fromNumber = null
    ) {
        $this->sid = $sid ?? config('services.twilio.sid');
        $this->token = $token ?? config('services.twilio.token');
        $this->fromNumber = $fromNumber ?? config('services.twilio.from');
    }

    public function send(string $phoneNumber, string $message): bool
    {
        // TODO: Implement direct Twilio REST API client or SDK
        Log::info(sprintf('[Twilio SMS Dummy] To: %s | Message: %s', $phoneNumber, $message));

        return true;
    }
}
