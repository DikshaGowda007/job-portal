<?php

namespace App\Modules\V1\User\Services\ForgotPassword;

use App\Constants\CommonConstant;
use App\Constants\EmailConstant;
use App\Constants\EmailTemplateConstants;
use App\Dto\PasswordResetOtpMailDto;
use App\Exceptions\UserNotFoundException;
use App\Mail\PasswordResetOtpMail;
use App\Modules\V1\Email\Bo\TemplateBo;
use App\Modules\V1\Email\Services\TemplateService;
use App\Repositories\DAO\V1\UserOTPVerificationDAO;
use App\Repositories\V1\UserOTPVerificationRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    private string $email;

    private Collection $userDetails;

    public function __construct(
        private UserRepository $userRepository,
        private UserOTPVerificationRepository $userOtpVerificationRepository,
        private UserOTPVerificationDAO $userOtpVerificationDao,
    ) {}

    public function sendResetOtp(string $email): JsonResponse
    {
        $this->email = $email;

        try {
            $this->userDetails = $this->findUser();

            $this->deleteExistingOtp($this->userDetails->get('id'));
            $this->insertOtp($this->userDetails->get('id'));
            $this->sendPasswordResetEmail();

            return response()->json(CommonUtils::successResponse('If the email exists, a password reset OTP has been sent'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to send reset OTP'));
        }
    }

    private function findUser(): Collection
    {
        $userDetails = collect($this->userRepository->findByEmail($this->email)->first());

        if ($userDetails->isEmpty()) {
            throw UserNotFoundException::withMessage('If the email exists, a password reset OTP has been sent');
        }

        return $userDetails;
    }

    private function deleteExistingOtp(int $userId): void
    {
        $this->userOtpVerificationDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->userOtpVerificationRepository->updateByUserId($userId, $this->userOtpVerificationDao);
    }

    private function insertOtp(int $userId): void
    {
        $this->prepareOtpDao($userId);
        $this->userOtpVerificationRepository->insert($this->userOtpVerificationDao);
    }

    private function prepareOtpDao(int $userId): void
    {
        $this->userOtpVerificationDao->setUserId($userId);
        $this->userOtpVerificationDao->setOtp(str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT));
        $this->userOtpVerificationDao->setExpiresAt(Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s'));
    }

    private function sendPasswordResetEmail(): void
    {
        $templateBo = $this->prepareTemplateBo();
        $emailDto = $this->prepareEmailDto();

        $templateService = app(TemplateService::class);
        $templateService->initiateEmailProcess($templateBo, $emailDto);
    }

    private function prepareTemplateBo(): TemplateBo
    {
        $templateBo = new TemplateBo;

        $templateBo->setTemplateCode(EmailTemplateConstants::FORGOT_PASSWORD_OTP_EMAIL);
        $templateBo->setModuleType(EmailConstant::MODULE_TYPE_GENERAL);
        $templateBo->setEmailId($this->userDetails->get('email'));
        $templateBo->setMailableClass(PasswordResetOtpMail::class);
        $templateBo->setMailableDtoClass(PasswordResetOtpMailDto::class);
        $templateBo->setUserId($this->userDetails->get('id'));

        return $templateBo;
    }

    private function prepareEmailDto(): PasswordResetOtpMailDto
    {
        $emailDto = new PasswordResetOtpMailDto;

        $emailDto->setUserName(trim($this->userDetails->get('first_name').' '.$this->userDetails->get('last_name')));
        $emailDto->setOtp($this->userOtpVerificationDao->getOtp());

        return $emailDto;
    }
}
