<?php

namespace App\Exceptions;

use Exception;

class TemplateCodeNotFoundException extends Exception
{
    public static function withMessage(?string $message = 'Template Code Not Found'): self
    {
        $exception = new TemplateCodeNotFoundException;

        $exception->message = $message;

        return $exception;
    }
}
