<?php

namespace App\Services\Otp\Providers;

use App\Services\Otp\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteSmsProvider implements SmsProviderInterface
{
    public function __construct(
        protected ?string $token = null
    ) {
        $this->token = $token ?? config('services.fonnte.token');
    }

    public function send(string $phoneNumber, string $message): bool
    {
        // If token is missing, fallback to logging
        if (empty($this->token)) {
            Log::warning('[Fonnte Provider] No API token configured. Falling back to log.');
            Log::info(sprintf('[Fonnte WhatsApp Dummy] To: %s | Message: %s', $phoneNumber, $message));

            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phoneNumber,
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('[Fonnte Provider Error] '.$e->getMessage());

            return false;
        }
    }
}
