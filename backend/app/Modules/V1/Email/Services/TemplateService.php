<?php

namespace App\Modules\V1\Email\Services;

use App\Constants\CommonConstant;
use App\Constants\EmailConstant;
use App\Exceptions\DataInsertFailed;
use App\Exceptions\InvalidDataException;
use App\Modules\V1\Email\Bo\TemplateBo;
use App\Modules\V1\Email\Dto\SendEmailDto;
use App\Repositories\DAO\V1\EmailLogsDAO;
use App\Repositories\V1\EmailLogsRepository;
use Exception;

class TemplateService
{
    private object $emailDto;

    public function __construct(
        private TemplateBo $templateBo,
        private SendEmailDto $sendEmailDto,
        private SendEmailService $sendEmailService,
        private EmailLogsDAO $emailLogsDao,
        private EmailLogsRepository $emailLogsRepository,
    ) {}

    public function initiateEmailProcess(TemplateBo $templateBo, object $dto)
    {
        try {
            $this->templateBo = $templateBo;
            $this->validateTemplateBo($templateBo);
            $this->validateDto($dto, $templateBo);
            $this->emailDto = $dto;
            $this->populateEmailDetails();
            $this->sendEmail();
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function validateTemplateBo(TemplateBo $templateBo): bool
    {
        if (
            $templateBo->getTemplateCode() !== null &&
            $templateBo->getEmailId() !== null &&
            $templateBo->getMailableClass() !== null &&
            class_exists($templateBo->getMailableClass())
        ) {
            return true;
        }
        throw InvalidDataException::withMessage('Email TemplateBo Validation Failed');
    }

    private function validateDto($dto, TemplateBo $templateBo)
    {
        $mailableClass = $templateBo->getMailableClass();
        $reflection = new \ReflectionClass($mailableClass);
        $constructor = $reflection->getConstructor();
        $paramType = $constructor->getParameters()[0]->getType();
        $expectedDtoClass = $paramType->getName();
        if (! is_a($dto, $expectedDtoClass)) {
            throw InvalidDataException::withMessage("Email DTO is not of expected type $expectedDtoClass.");
        }

        return $dto->validate();
    }

    private function populateEmailDetails(): void
    {

        $this->sendEmailDto->setUserId($this->templateBo->getUserId());
        $this->sendEmailDto->setTemplateCode($this->templateBo->getTemplateCode());
        $this->sendEmailDto->setModuleType($this->templateBo->getModuleType());
        $this->sendEmailDto->setToEmail($this->templateBo->getEmailId());
        $this->sendEmailDto->setMailableClass($this->templateBo->getMailableClass());

        $template = $this->sendEmailService->getActiveEmailTemplate($this->sendEmailDto);
        $this->templateBo->setTemplate($template);
    }

    private function sendEmail(): void
    {
        try {
            $emailResponse = $this->sendEmailService->sendEmail($this->sendEmailDto, $this->emailDto);
            $this->handleEmailResponse($emailResponse);
        } catch (Exception $e) {
            $this->handleEmailResponse(false);
        }
    }

    public function handleEmailResponse(bool $emailResponse): void
    {
        try {
            if ($emailResponse) {
                $this->logEmailSent(EmailConstant::EMAIL_SENT_STATUS_SUCCESS);
            } else {
                $this->logEmailSent(EmailConstant::EMAIL_SENT_STATUS_FAILURE);
            }
        } catch (Exception $e) {
            throw DataInsertFailed::withMessage('data insert failed for email logs');
        }
    }

    /**
     * Log the email into EmailNotificationLog table.
     */
    private function logEmailSent(int $status): void
    {
        $this->emailLogsDao->setEmailTemplateId($this->templateBo->getTemplate()->get('email_template_id'));
        $this->emailLogsDao->setEmailVendorAccountId($this->sendEmailDto->getEmailVendorAccountId());
        $this->emailLogsDao->setToEmailId($this->sendEmailDto->getToEmail());
        $this->emailLogsDao->setEmailBody(json_encode($this->emailDto->toArray()));
        $this->emailLogsDao->setUserId($this->templateBo->getUserId());
        $this->emailLogsDao->setIsSentReceived(EmailConstant::IS_SENT_RECIEVED_YES);
        $this->emailLogsDao->setStatus($status);
        $this->emailLogsDao->setIsDeleted(CommonConstant::IS_DELETED_NO);

        try {
            $this->emailLogsRepository->insert($this->emailLogsDao);
        } catch (Exception $e) {
            throw DataInsertFailed::withMessage('data insert failed for email logs');
        }
    }
}
