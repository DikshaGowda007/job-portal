<?php

namespace App\Repositories\V1;

use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use Illuminate\Support\Collection;

interface UserOTPVerificationRepository
{
    public function insert(UserOTPVerificationDAO $userOTPVerificationDAO): int;

    public function findByEmailAndOtp(string $email, string $otp): Collection;
    
    public function findByEmailAndOtpAndExpiry(string $email, string $otp): Collection;
}
