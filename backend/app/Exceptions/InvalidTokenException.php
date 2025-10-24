<?php

namespace App\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    /**
     * Constructor with message
     *
     * @return InvalidTokenException
     */
    public static function withMessage()
    {
        $exception = app(InvalidTokenException::class);
        $exception->message = 'Invalid Token.';
        $exception->code = '401';

        return $exception;
    }
}
