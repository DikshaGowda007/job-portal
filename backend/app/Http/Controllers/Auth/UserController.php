<?php

namespace App\Http\Controllers\Auth;

use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\User\Add\UserOtpVerificationRequest;
use App\Http\Requests\V1\User\Add\UserLoginRequest;
use App\Http\Requests\V1\User\Add\UserRequest;
use App\Modules\Auth\Signup\Services\LoginService;
use App\Modules\Auth\Signup\Services\SignupService;
use App\Modules\Auth\Signup\Services\OtpVerificationService;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

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
        $email = $userOTPVerificationRequest->input('email');
        $otp = $userOTPVerificationRequest->input('otp');
        try {
            $otpVerificationService = app(OtpVerificationService::class);
            return response()->json($otpVerificationService->verifyOtp($email, $otp));
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function login(UserLoginRequest $userLoginRequest): JsonResponse
    {
        try {
            $email = $userLoginRequest->input('email');
            $password = $userLoginRequest->input('password');
            $browserIp = $userLoginRequest->header('x-client-ip') ?? $userLoginRequest->ip();
            $userAgent = $userLoginRequest->header('x-client-user-agent') ?? $userLoginRequest->userAgent();
            $loginService = app(LoginService::class);
            return response()->json($loginService->add($email, $password, $browserIp, $userAgent));
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }
}
