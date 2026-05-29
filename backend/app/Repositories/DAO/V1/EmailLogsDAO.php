<?php

namespace App\Repositories\DAO\V1;

class EmailLogsDAO
{
    private ?int $emailTemplateId = null;

    private ?int $emailVendorAccountId = null;

    private ?string $toEmailId = null;

    private ?string $emailSubject = null;

    private ?string $emailBody = null;

    private ?int $userId = null;

    private ?int $onboardUserId = null;

    private ?int $omRowId = null;

    private ?int $isSentReceived = null;

    private ?int $status = null;

    private ?int $isDeleted = null;

    public function toArray(): array
    {
        $data = [];

        if (isset($this->emailTemplateId)) {
            $data['email_template_id'] = $this->emailTemplateId;
        }
        if (isset($this->emailVendorAccountId)) {
            $data['email_vendor_account_id'] = $this->emailVendorAccountId;
        }
        if (isset($this->toEmailId)) {
            $data['to_email_id'] = $this->toEmailId;
        }
        if (isset($this->emailSubject)) {
            $data['email_subject'] = $this->emailSubject;
        }
        if (isset($this->emailBody)) {
            $data['email_body'] = $this->emailBody;
        }
        if (isset($this->userId)) {
            $data['user_id'] = $this->userId;
        }
        if (isset($this->onboardUserId)) {
            $data['onboard_user_id'] = $this->onboardUserId;
        }
        if (isset($this->omRowId)) {
            $data['om_row_id'] = $this->omRowId;
        }
        if (isset($this->isSentReceived)) {
            $data['is_sent_received'] = $this->isSentReceived;
        }
        if (isset($this->status)) {
            $data['status'] = $this->status;
        }
        if (isset($this->isDeleted)) {
            $data['is_deleted'] = $this->isDeleted;
        }

        return $data;
    }

    public function setEmailTemplateId(?int $value): self
    {
        $this->emailTemplateId = $value;

        return $this;
    }

    public function setEmailVendorAccountId(?int $value): self
    {
        $this->emailVendorAccountId = $value;

        return $this;
    }

    public function setToEmailId(?string $value): self
    {
        $this->toEmailId = $value;

        return $this;
    }

    public function setEmailSubject(?string $value): self
    {
        $this->emailSubject = $value;

        return $this;
    }

    public function setEmailBody(?string $value): self
    {
        $this->emailBody = $value;

        return $this;
    }

    public function setUserId(?int $value): self
    {
        $this->userId = $value;

        return $this;
    }

    public function SetOnboardUserId(?int $value): self
    {
        $this->onboardUserId = $value;

        return $this;
    }

    public function setOmRowId(?int $value): self
    {
        $this->omRowId = $value;

        return $this;
    }

    public function setIsSentReceived(?int $value): self
    {
        $this->isSentReceived = $value;

        return $this;
    }

    public function setStatus(?int $value): self
    {
        $this->status = $value;

        return $this;
    }

    public function setIsDeleted(?int $value): self
    {
        $this->isDeleted = $value;

        return $this;
    }
}
