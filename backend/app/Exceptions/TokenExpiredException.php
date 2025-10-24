<?php

namespace App\Exceptions;

use Exception;

class TokenExpiredException extends Exception
{
    /**
     * Constructor with message
     *
     * @return TokenExpiredException
     */
    public static function withMessage()
    {
        $exception = app(TokenExpiredException::class);
        $exception->message = 'Token has expired.';
        $exception->code = '401';

        return $exception;
    }
}
