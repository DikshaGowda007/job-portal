<?php

namespace App\Repositories\MySql\V1;

use App\Models\UserOTPVerification;
use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserOTPVerificationRepositoryImpl implements UserOTPVerificationRepository
{
    public function insert(UserOTPVerificationDAO $userOTPVerificationDAO): int
    {
        $userOTPVerificationDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return UserOTPVerification::create($userOTPVerificationDAO->toArray())->id;
    }

    public function findByUserIdAndOtp(int $userId, string $otp): Collection
    {
        return UserOTPVerification::where('user_id', $userId)
            ->where('otp', $otp)
            ->get();
    }

    public function updateByUserId(int $userId, UserOTPVerificationDAO $userOTPVerificationDAO)
    {
        return UserOTPVerification::where('user_id', $userId)
            ->update($userOTPVerificationDAO->toArray());
    }
}
