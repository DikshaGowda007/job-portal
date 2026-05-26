<?php

namespace App\Http\Controllers\Auth;

use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\User\Add\ResendOtpRequest;
use App\Http\Requests\V1\User\Add\UserLoginRequest;
use App\Http\Requests\V1\User\Add\UserOtpVerificationRequest;
use App\Http\Requests\V1\User\Add\UserRequest;
use App\Http\Requests\V1\User\ForgotPassword\DetailsRequest as ForgotPasswordRequest;
use App\Http\Requests\V1\User\ResetPassword\DetailsRequest as ResetPasswordRequest;
use App\Modules\Auth\Services\LogoutService;
use App\Modules\Auth\Signup\Services\LoginService;
use App\Modules\Auth\Signup\Services\SignupService;
use App\Modules\Auth\Signup\Services\OtpVerificationService;
use App\Modules\Auth\Signup\Services\ResendOtpService;
use App\Modules\V1\User\Services\ForgotPassword\DetailsService as ForgotPasswordService;
use App\Modules\V1\User\Services\ResetPassword\DetailsService as ResetPasswordService;
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

    public function resendOtp(ResendOtpRequest $resendOtpRequest): JsonResponse
    {
        try {
            $email = $resendOtpRequest->input('email');
            $resendOtpService = app(ResendOtpService::class);

            return $resendOtpService->resend($email);
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest): JsonResponse
    {
        try {
            $forgotPasswordService = app(ForgotPasswordService::class);
            $email = $forgotPasswordRequest->input('email');

            return $forgotPasswordService->sendResetOtp($email);
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest): JsonResponse
    {
        try {
            $resetPasswordService = app(ResetPasswordService::class);
            $resetPasswordDetailsBo = $resetPasswordService->prepareBo($resetPasswordRequest);

            return $resetPasswordService->resetPassword($resetPasswordDetailsBo);
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $logoutService = app(LogoutService::class);

            return $logoutService->logout();
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
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
