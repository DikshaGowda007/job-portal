<?php

namespace App\Exceptions;

use Exception;

class AccountInActiveException extends Exception
{
    /**
     * Constructor with message
     *
     * @return AccountInActiveException
     */
    public static function withMessage(?string $message = 'Account is Inactive. Try to contact Admin.')
    {
        $exception = app(AccountInActiveException::class);
        $exception->message = $message;

        return $exception;
    }
}
