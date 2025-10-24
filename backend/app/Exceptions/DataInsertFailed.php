<?php

namespace App\Exceptions;

use Exception;

class DataInsertFailed extends Exception
{
    public static function withMessage(?string $msg = 'Data Insert Failed')
    {
        $exception = app(DataInsertFailed::class);
        $exception->message = $msg;

        return $exception;
    }
}
