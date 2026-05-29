<?php

namespace App\Exceptions;

use Exception;

class SendEmailClassNotFoundException extends Exception
{
    /**
     * Create a new SendEmailClassNotFoundException instance with a custom message.
     */
    public static function withMessage(?string $message = 'Email Class Not Found')
    {
        $exception = new SendEmailClassNotFoundException;
        $exception->message = $message;

        return $exception;
    }
}
