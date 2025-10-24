<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Exceptions\DataInsertFailed;
use App\Mail\SendOtpMail;
use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use Carbon\Carbon;
use Mail;

class OtpService
{
    public function __construct(private UserOTPVerificationDAO $userOTPVerificationDAO, private UserOTPVerificationRepository $userOTPVerificationRepository)
    {}

    public function sendOtp(int $userId, string $email)
    {
        try {
            $this->insert($userId);
            $this->sendMail($email);
            return ['status' => CommonConstant::OTP_SENT_SUCCESS, 'user_id' => $userId];
        } catch (\Throwable $e) {
            \Log::error('OTP Send Error: ' . $e->getMessage());
            return ['status' => CommonConstant::ERROR, 'message' => CommonConstant::OTP_SENT_FAIL];
        }
    }

    private function insert(int $userId): int
    {
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $this->prepareUserVerificationDAO($userId, $otp, $expiresAt);

        $insertedId = $this->userOTPVerificationRepository->insert($this->userOTPVerificationDAO);

        return !$insertedId ? throw DataInsertFailed::withMessage() : $insertedId;
    }

    private function prepareUserVerificationDAO(int $userId, string $otp, string $expiresAt): UserOTPVerificationDAO
    {
        $this->userOTPVerificationDAO->setUserId($userId);
        $this->userOTPVerificationDAO->setOtp($otp);
        $this->userOTPVerificationDAO->setExpiresAt($expiresAt);
        return $this->userOTPVerificationDAO;
    }

    private function sendMail(string $email): void
    {
        Mail::to($email)->send(new SendOtpMail($this->userOTPVerificationDAO->getOtp()));
    }
}
