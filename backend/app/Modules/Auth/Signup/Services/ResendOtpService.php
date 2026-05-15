<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Exceptions\InvalidDataException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ResendOtpService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserOTPVerificationRepository $userOTPVerificationRepository,
        private OtpService $otpService,
        private UserOTPVerificationDAO $userOTPVerificationDAO,
    ) {}

    public function resend(string $email): JsonResponse
    {
        try {
            $user = $this->fetchUser($email);
            $this->updateUserOTPVerification($user->get('id'));
            $this->otpService->sendOtp($user->get('id'), $email);

            return response()->json(CommonUtils::successResponse('OTP sent successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to resend OTP. Please try again.'));
        }
    }

    private function fetchUser(string $email): Collection
    {
        $user = collect($this->userRepository->findByEmail($email)->first());

        if ($user->isEmpty()) {
            throw UserNotFoundException::withMessage();
        }

        if ($user->get('verified') != UserConstant::IS_VERIFIED) {
            throw InvalidDataException::withMessage('Account is already verified');
        }

        return $user;
    }

    private function updateUserOTPVerification(int $userId)
    {
        $this->userOTPVerificationDAO->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->userOTPVerificationRepository->updateByUserId($userId, $this->userOTPVerificationDAO);

    }
}
