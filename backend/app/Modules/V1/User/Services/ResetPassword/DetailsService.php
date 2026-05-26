<?php

namespace App\Modules\V1\User\Services\ResetPassword;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\User\ResetPassword\DetailsRequest;
use App\Modules\V1\User\Bo\ResetPassword\DetailsBo;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DetailsService
{
    private DetailsBo $resetPasswordBo;

    public function __construct(
        private UserRepository $userRepository,
        private UserOTPVerificationRepository $userOtpVerificationRepository,
        private UserDAO $userDao,
        private UserOTPVerificationDAO $userOtpVerificationDao,
    ) {}

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        $resetPasswordBo = new DetailsBo;
        $resetPasswordBo->setEmail($request->input('email'));
        $resetPasswordBo->setOtp($request->input('otp'));
        $resetPasswordBo->setNewPassword($request->input('new_password'));

        return $resetPasswordBo;
    }

    public function resetPassword(DetailsBo $bo): JsonResponse
    {
        $this->resetPasswordBo = $bo;

        try {
            $user = $this->findUser();
            $this->verifyOtp($user->get('id'));
            $this->performUpdate($user->get('id'));

            return response()->json(CommonUtils::successResponse('Password reset successfully'));
        } catch (DataNotFoundException $e) {

            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to reset password'));
        }
    }

    private function findUser(): Collection
    {
        $user = collect($this->userRepository->findByEmail($this->resetPasswordBo->getEmail())->first());

        if ($user->isEmpty()) {
            throw DataNotFoundException::withMessage('Invalid email or OTP');
        }

        return $user;
    }

    private function verifyOtp(int $userId): void
    {
        $otpRecord = $this->userOtpVerificationRepository->findByUserIdAndOtp($userId, $this->resetPasswordBo->getOtp());

        if ($otpRecord->isEmpty()) {
            throw DataNotFoundException::withMessage('Invalid or expired OTP');
        }
    }

    private function performUpdate(int $userId): void
    {
        $this->prepareUserDao();
        $this->userRepository->updateById($userId, $this->userDao);

        $this->userOtpVerificationDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->userOtpVerificationRepository->updateByUserId($userId, $this->userOtpVerificationDao);
    }

    private function prepareUserDao(): void
    {
        $this->userDao->setPassword(Hash::make($this->resetPasswordBo->getNewPassword()));
    }
}
