<?php

namespace App\Exceptions;

use Exception;

class OtpInvalidException extends Exception
{
    public function __construct(
        string $message = 'Kode OTP tidak valid atau telah kedaluwarsa.',
        public ?string $reason = null,
        public int $attemptsLeft = 0,
        int $code = 422
    ) {
        parent::__construct($message, $code);
    }
}
