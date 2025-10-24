<?php

namespace App\Exceptions;

use Exception;

class InvalidOTP extends Exception
{
    public static function withMessage(?string $msg = 'Invalid Otp')
    {
        $exception = app(InvalidOTP::class);
        $exception->message = $msg;

        return $exception;
    }
}
