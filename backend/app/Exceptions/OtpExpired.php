<?php

namespace App\Exceptions;

use App\Constants\CommonConstant;
use Exception;

class OtpExpired extends Exception
{
    public static function withMessage(?string $msg = CommonConstant::OTP_EXPIRED)
    {
        $exception = app(OtpExpired::class);
        $exception->message = $msg;

        return $exception;
    }
}
