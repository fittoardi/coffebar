<?php

namespace App\Services\Otp\Contracts;

interface SmsProviderInterface
{
    /**
     * Send an OTP or message to the given phone number.
     *
     * @param  string  $phoneNumber  Target phone number in international or local format (e.g. 0812... or 62812...)
     * @param  string  $message  The SMS or WhatsApp message content
     * @return bool True if message dispatch was successful
     */
    public function send(string $phoneNumber, string $message): bool;
}
