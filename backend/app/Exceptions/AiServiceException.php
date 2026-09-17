<?php

namespace App\Exceptions;

use Exception;

class AiServiceException extends Exception
{
    public static function withMessage(?string $msg = 'AI service is currently unavailable'): self
    {
        $exception = app(AiServiceException::class);
        $exception->message = $msg;

        return $exception;
    }
}
