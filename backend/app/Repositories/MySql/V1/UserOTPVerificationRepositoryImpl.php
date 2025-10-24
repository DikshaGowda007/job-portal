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

    public function findByEmailAndOtp(string $email, string $otp): Collection
    {
        return UserOTPVerification::where('email', $email)
            ->where('otp', $otp)
            ->get();
    }

    public function findByEmailAndOtpAndExpiry(string $email, string $otp): Collection
    {
        return UserOTPVerification::where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->get();
    }
}
