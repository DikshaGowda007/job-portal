<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public static function withMessage(?string $msg = 'User Not Found')
    {
        $exception = app(UserNotFoundException::class);
        $exception->message = $msg;

        return $exception;
    }
}
