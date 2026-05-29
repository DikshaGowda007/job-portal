<?php

namespace App\Modules\V1\Email\Services;

use App\Constants\CommonConstant;
// use App\Consumers\V1\Email\Constants\EmailConstant;
use App\Constants\EmailConstant;
// use App\Consumers\V1\Email\Exceptions\TemplateCodeNotFoundException;
use App\Exceptions\SendEmailClassNotFoundException;
use App\Exceptions\TemplateCodeNotFoundException;
use App\Modules\V1\Email\Dto\SendEmailDto;
use App\Repositories\V1\EmailTemplateRepository;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendEmailService
{
    public function __construct(
        private EmailTemplateRepository $emailTemplateRepository,
        private SendEmailDto $sendEmailDto
    ) {}

    public function getActiveEmailTemplate(SendEmailDto $sendEmailDto): Collection
    {
        $templateCode = $sendEmailDto->getTemplateCode();

        // Validate required parameters
        if (empty($templateCode)) {
            throw TemplateCodeNotFoundException::withMessage('Template Code Not Found '.$templateCode);
        }

        // Retrieve active templates for given user(s), template code, and vendor
        $emailTemplate = $this->getEmailTemplate($templateCode, CommonConstant::STATUS_ACTIVE);

        return collect($emailTemplate);
    }

    public function getEmailTemplate(string $templateCode, int $status): Collection
    {
        $emailTemplate = $this->emailTemplateRepository->findByTemplateCodeAndStatus($templateCode, $status);
        if ($emailTemplate->isEmpty()) {
            throw TemplateCodeNotFoundException::withMessage();
        }

        return $emailTemplate;
    }

    public function sendEmail(SendEmailDto $sendEmailDto, object $templateDto): bool
    {
        try {
            $this->sendEmailDto = $sendEmailDto;
            $this->send($templateDto);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function send(object $templateDto)
    {
        $toEmail = $this->sendEmailDto->getToEmail();
        $mailer = env('MAIL_MAILER');

        $moduleType = $this->sendEmailDto->getModuleType();
        if ($moduleType == EmailConstant::MODULE_TYPE_GENERAL) {
            $mailableClass = $this->sendEmailDto->getMailableClass();
            Mail::mailer($mailer)->to($toEmail)->send(new $mailableClass($templateDto));
        } else {
            throw SendEmailClassNotFoundException::withMessage('Email Class Not Found For Module Type : '.$moduleType);
        }

    }
}
