<?php

namespace App\Repositories\V1;

use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use Illuminate\Support\Collection;

interface UserOTPVerificationRepository
{
    public function insert(UserOTPVerificationDAO $userOTPVerificationDAO): int;

    public function findByUserIdAndOtp(int $userId, string $otp): Collection;

    public function updateByUserId(int $userId, UserOTPVerificationDAO $userOTPVerificationDAO);
}
