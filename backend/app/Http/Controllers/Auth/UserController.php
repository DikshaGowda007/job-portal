<?php

namespace App\Http\Controllers\Auth;

use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\AccessRights\Edit\DetailsRequest as EditAccessRightDetailsRequest;
use App\Http\Requests\V1\AccessRights\Get\DetailsRequest as GetAccessRightDetailsRequest;
use App\Http\Requests\V1\User\Add\ResendOtpRequest;
use App\Http\Requests\V1\User\Add\UserLoginRequest;
use App\Http\Requests\V1\User\Add\UserOtpVerificationRequest;
use App\Http\Requests\V1\User\Add\UserRequest;
use App\Http\Requests\V1\User\ForgotPassword\DetailsRequest as ForgotPasswordRequest;
use App\Http\Requests\V1\User\ResetPassword\DetailsRequest as ResetPasswordRequest;
use App\Modules\Auth\Helpers\UserHelper;
use App\Modules\Auth\Login\Services\LoginService;
use App\Modules\Auth\Services\LogoutService;
use App\Modules\Auth\Services\RefreshTokenService;
use App\Modules\Auth\Signup\Services\OtpVerificationService;
use App\Modules\Auth\Signup\Services\ResendOtpService;
use App\Modules\Auth\Signup\Services\SignupService;
use App\Modules\V1\AccessRights\Services\Edit\DetailsService as EditAccessRightDetailsService;
use App\Modules\V1\AccessRights\Services\Get\DetailsService as GetAccessRightDetailsService;
use App\Modules\V1\User\Services\ForgotPassword\DetailsService as ForgotPasswordService;
use App\Modules\V1\User\Services\ResetPassword\DetailsService as ResetPasswordService;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function signup(UserRequest $userRequest): JsonResponse
    {
        try {
            $userHelper = app(UserHelper::class);
            $signupService = app(SignupService::class);
            $userDetailsBo = $userHelper->prepareBo($userRequest);

            return $signupService->add($userDetailsBo);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function verifyOtp(UserOtpVerificationRequest $userOtpVerificationRequest): JsonResponse
    {
        try {
            $userId = (int) $userOtpVerificationRequest->input('user_id');
            $otp = $userOtpVerificationRequest->input('otp');
            $otpVerificationService = app(OtpVerificationService::class);

            return response()->json($otpVerificationService->verifyOtp($userId, $otp));
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

    public function refresh(): JsonResponse
    {
        try {
            $refreshTokenService = app(RefreshTokenService::class);

            return $refreshTokenService->refresh();
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

    public function logout(Request $request): JsonResponse
    {
        try {
            $logoutService = app(LogoutService::class);

            return $logoutService->logout($request);
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    public function getAccessRights(GetAccessRightDetailsRequest $getAccessRightDetailsRequest): JsonResponse
    {
        try {
            $getAccessRightDetailsService = app(GetAccessRightDetailsService::class);

            return $getAccessRightDetailsService->get((int) $getAccessRightDetailsRequest->input('user_id'));
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function editAccessRights(EditAccessRightDetailsRequest $editAccessRightDetailsRequest): JsonResponse
    {
        try {
            $editAccessRightDetailsService = app(EditAccessRightDetailsService::class);
            $editAccessRightDetailsBo = $editAccessRightDetailsService->prepareBo($editAccessRightDetailsRequest);

            return $editAccessRightDetailsService->edit($editAccessRightDetailsBo);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function login(UserLoginRequest $userLoginRequest): JsonResponse
    {
        try {
            $loginService = app(LoginService::class);
            $email = $userLoginRequest->input('email');
            $password = $userLoginRequest->input('password');

            return response()->json($loginService->add($email, $password));
        } catch (\Throwable $e) {
            \Log::error('Login error: '.$e->getMessage().' | '.$e->getFile().':'.$e->getLine());

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }
}
