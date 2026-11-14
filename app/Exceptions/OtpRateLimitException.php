<?php

namespace App\Exceptions;

use Exception;

class OtpRateLimitException extends Exception
{
    public function __construct(
        string $message = 'Permintaan OTP telah mencapai batas maksimal (maksimal 3 kali per 10 menit). Silakan coba lagi nanti.',
        int $code = 429
    ) {
        parent::__construct($message, $code);
    }
}
