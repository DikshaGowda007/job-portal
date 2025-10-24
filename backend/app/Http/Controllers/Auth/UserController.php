<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\V1\User\Add\UserOtpVerificationRequest;
use App\Http\Requests\V1\User\Add\UserRequest;
use App\Modules\Auth\Signup\Services\SignupService;
use App\Modules\Auth\Signup\Services\OtpVerificationService;

class UserController
{
    public function signup(UserRequest $userRequest)
    {
        try {
            $signupService = app(SignupService::class);
            $userDetailsBO = $signupService->prepareBo($userRequest);
            return $signupService->add($userDetailsBO);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function verifyOtp(UserOtpVerificationRequest $userOTPVerificationRequest)
    {
        $userId = $userOTPVerificationRequest->input('user_id');
        $otp = $userOTPVerificationRequest->input('otp');
        try {
            $otpVerificationService = app(OtpVerificationService::class);
            return response()->json($otpVerificationService->verifyOtp($userId, $otp));
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
