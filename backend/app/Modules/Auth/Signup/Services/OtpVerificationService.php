<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Exceptions\InvalidOTP;
use App\Exceptions\OtpExpired;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use App\Repositories\V1\UserRepository;
use Exception;
use Illuminate\Support\Collection;


class OtpVerificationService
{
    public function __construct(private UserOTPVerificationRepository $userOTPVerificationRepository, private UserRepository $userRepository, private UserDAO $userDAO)
    {}

    public function verifyOtp(int $userId, string $otp): array
    {
        try {
            $this->validateOtp($userId, $otp);
            $this->updateUser($userId);
            return ['status' => CommonConstant::SUCCESS, 'message' => 'OTP verified successfully'];
        } catch (Exception | InvalidOTP $e) {
            return ['status' => CommonConstant::ERROR, 'message' => $e->getMessage()];
        } catch (\Throwable $e) {
            return ['status' => CommonConstant::ERROR, 'message' => 'Failed to verify OTP'];
        }
    }

    private function validateOtp(int $userId, string $otp): Collection
    {
        // Step 1️⃣ — Check if OTP exists
        $otpRecord = $this->findOtp($userId, $otp);

        // Step 2️⃣ — Check if OTP is expired
        $this->findOtpExpiry($userId, $otp);

        return $otpRecord;
    }

    /**
     * Step 1: Check if OTP exists for given user.
     */
    private function findOtp(int $userId, string $otp): Collection
    {
        $record = $this->userOTPVerificationRepository->findByUserIdAndOtp($userId, $otp);

        if ($record->isEmpty()) {
            throw InvalidOTP::withMessage('Incorrect OTP. Please try again.');
        }

        return $record;
    }

    /**
     * Step 2: Check if OTP is expired (separate DB query).
     */
    private function findOtpExpiry(int $userId, string $otp): void
    {
        $record = $this->userOTPVerificationRepository->findByUserIdAndOtpAndExpiry($userId, $otp);

        if ($record->isEmpty()) {
            throw OtpExpired::withMessage();
        }
    }

    public function updateUser(int $userId): bool
    {
        $this->userDAO->setVerified(CommonConstant::IS_VERIFIED_USER);
        return $this->userRepository->updateById($userId, $this->userDAO);
    }
}