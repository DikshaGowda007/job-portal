<?php

namespace App\Modules\V1\Email\Dto;

use Illuminate\Support\Collection;

class SendEmailDto
{
    private ?int $emailVendorAccountId = null;

    private ?string $toEmail = null;

    private ?array $decryptedCredential = null;

    private ?Collection $emailVendorAccountData = null;

    private ?int $userId = null;

    private ?int $emailVendorId = null;

    private ?int $moduleType = null;

    private ?string $className = null;

    private ?string $mailableClass = null;

    private ?string $templateCode = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->emailVendorAccountId)) {
            $collection['email_vendor_account_id'] = $this->emailVendorAccountId;
        }

        if (isset($this->toEmail)) {
            $collection['to_email'] = $this->toEmail;
        }

        if (isset($this->templateCode)) {
            $collection['template_code'] = $this->templateCode;
        }

        if (isset($this->decryptedCredential)) {
            $collection['decrypted_credential'] = $this->decryptedCredential;
        }

        if (isset($this->emailVendorAccountData)) {
            $collection['email_vendor_account_data'] = $this->emailVendorAccountData;
        }

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }

        if (isset($this->emailVendorId)) {
            $collection['email_vendor_id'] = $this->emailVendorId;
        }

        if (isset($this->moduleType)) {
            $collection['module_type'] = $this->moduleType;
        }

        if (isset($this->className)) {
            $collection['class_name'] = $this->className;
        }

        if (isset($this->mailableClass)) {
            $collection['mailable_class'] = $this->mailableClass;
        }

        return $collection;
    }

    /**
     * Get the value of emailVendorAccountId
     */
    public function getEmailVendorAccountId(): ?int
    {
        return $this->emailVendorAccountId;
    }

    /**
     * Set the value of emailVendorAccountId
     */
    public function setEmailVendorAccountId(?int $emailVendorAccountId): self
    {
        $this->emailVendorAccountId = $emailVendorAccountId;

        return $this;
    }

    /**
     * Get the value of toEmail
     */
    public function getToEmail(): ?string
    {
        return $this->toEmail;
    }

    /**
     * Set the value of toEmail
     */
    public function setToEmail(?string $toEmail): self
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    /**
     * Get the value of decryptedCredential
     */
    public function getDecryptedCredential(): ?array
    {
        return $this->decryptedCredential;
    }

    /**
     * Set the value of decryptedCredential
     */
    public function setDecryptedCredential(?array $decryptedCredential): self
    {
        $this->decryptedCredential = $decryptedCredential;

        return $this;
    }

    /**
     * Get the value of emailVendorAccountData
     */
    public function getEmailVendorAccountData(): ?Collection
    {
        return $this->emailVendorAccountData;
    }

    /**
     * Set the value of emailVendorAccountData
     */
    public function setEmailVendorAccountData(?Collection $emailVendorAccountData): self
    {
        $this->emailVendorAccountData = $emailVendorAccountData;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of emailVendorId
     */
    public function getEmailVendorId(): ?int
    {
        return $this->emailVendorId;
    }

    /**
     * Set the value of emailVendorId
     */
    public function setEmailVendorId(?int $emailVendorId): self
    {
        $this->emailVendorId = $emailVendorId;

        return $this;
    }

    /**
     * Get the value of moduleType
     */
    public function getModuleType(): ?int
    {
        return $this->moduleType;
    }

    /**
     * Set the value of moduleType
     */
    public function setModuleType(?int $moduleType): self
    {
        $this->moduleType = $moduleType;

        return $this;
    }

    /**
     * Get the value of className
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * Set the value of className
     */
    public function setClassName(?string $className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the value of mailableClass
     */
    public function getMailableClass(): ?string
    {
        return $this->mailableClass;
    }

    /**
     * Set the value of mailableClass
     */
    public function setMailableClass(?string $mailableClass): self
    {
        $this->mailableClass = $mailableClass;

        return $this;
    }

    /**
     * Get the value of templateCode
     */
    public function getTemplateCode(): ?string
    {
        return $this->templateCode;
    }

    /**
     * Set the value of templateCode
     */
    public function setTemplateCode(?string $templateCode): self
    {
        $this->templateCode = $templateCode;

        return $this;
    }
}
