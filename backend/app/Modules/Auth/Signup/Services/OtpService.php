<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Exceptions\DataInsertFailed;
use App\Exceptions\DataNotFoundException;
use App\Mail\SendOtpMail;
use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use Carbon\Carbon;
use Exception;
use Mail;

class OtpService
{
    public function __construct(
        private UserOTPVerificationDAO $userOtpVerificationDao,
        private UserOTPVerificationRepository $userOtpVerificationRepository
    ) {}

    public function sendOtp(int $userId, string $email)
    {
        try {
            $this->insert($userId);
            $this->sendMail($email);

            return ['status' => CommonConstant::OTP_SENT_SUCCESS, 'user_id' => $userId];
        } catch (Exception $e) {
            \Log::error('OTP Send Error: '.$e->getMessage());

            return ['status' => CommonConstant::ERROR, 'message' => CommonConstant::OTP_SENT_FAIL];
        } catch (\Throwable $e) {
            \Log::error('OTP Send Error: '.$e->getMessage());

            return ['status' => CommonConstant::ERROR, 'message' => CommonConstant::OTP_SENT_FAIL];
        }
    }

    private function insert(int $userId): int
    {
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $this->prepareUserVerificationDao($userId, $otp, $expiresAt);

        $insertedId = $this->userOtpVerificationRepository->insert($this->userOtpVerificationDao);

        return ! $insertedId ? throw DataInsertFailed::withMessage() : $insertedId;
    }

    private function prepareUserVerificationDao(int $userId, string $otp, string $expiresAt): UserOTPVerificationDAO
    {
        $this->userOtpVerificationDao->setUserId($userId);
        $this->userOtpVerificationDao->setOtp($otp);
        $this->userOtpVerificationDao->setExpiresAt($expiresAt);

        return $this->userOtpVerificationDao;
    }

    private function sendMail(string $email): void
    {
        try {
            $otp = $this->userOtpVerificationDao->getOtp();

            if (empty($otp)) {
                throw DataNotFoundException::withMessage("OTP not found while sending email to {$email}");
            }

            Mail::to($email)->send(new SendOtpMail($otp));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
