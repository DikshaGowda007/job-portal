<?php

namespace App\Exceptions;

use Exception;

class InvalidJobException extends Exception
{
    /**
     * Constructor with message
     *
     * @return InvalidJobException
     */
    public static function withMessage(?string $msg = 'Invalid Job')
    {
        $exception = app(InvalidJobException::class);
        $exception->message = $msg;

        return $exception;
    }
}
