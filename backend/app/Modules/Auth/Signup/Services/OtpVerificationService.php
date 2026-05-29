<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Exceptions\InvalidOTP;
use App\Exceptions\OtpExpired;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use App\Repositories\V1\UserRepository;
use Carbon\Carbon;
use Exception;

class OtpVerificationService
{
    public function __construct(
        private UserOTPVerificationRepository $userOtpVerificationRepository,
        private UserRepository $userRepository,
        private UserDAO $userDao
    ) {}

    public function verifyOtp(int $userId, string $otp): array
    {
        try {
            $this->validateOtp($userId, $otp);
            $this->updateUser($userId);

            return ['status' => CommonConstant::SUCCESS, 'message' => 'OTP verified successfully'];
        } catch (Exception|InvalidOTP $e) {
            return ['status' => CommonConstant::ERROR, 'message' => $e->getMessage()];
        } catch (\Throwable $e) {
            return ['status' => CommonConstant::ERROR, 'message' => 'Failed to verify OTP'];
        }
    }

    private function validateOtp(int $userId, string $otp)
    {
        $otpRecord = collect($this->userOtpVerificationRepository->findByUserIdAndOtp($userId, $otp)->first());

        if ($otpRecord->isEmpty()) {
            throw InvalidOTP::withMessage('Incorrect OTP. Please try again.');
        }

        if ($otpRecord->get('expires_at') < Carbon::now()->format('Y-m-d H:i:s')) {
            throw OtpExpired::withMessage();
        }
    }

    public function updateUser(int $userId): bool
    {
        $this->userDao->setVerified(CommonConstant::IS_VERIFIED_USER);

        return $this->userRepository->updateById($userId, $this->userDao);
    }
}
