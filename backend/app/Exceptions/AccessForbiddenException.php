<?php

namespace App\Exceptions;

use Exception;

class AccessForbiddenException extends Exception
{
    /**
     * Constructor with message
     *
     * @return AccessForbiddenException
     */
    public static function withMessage()
    {
        $exception = app(AccessForbiddenException::class);
        $exception->message = 'Access Forbidden.';
        $exception->code = '403';

        return $exception;
    }
}
