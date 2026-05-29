<?php

namespace App\Modules\V1\Email\Bo;

use Illuminate\Support\Collection;

class TemplateBo
{
    private ?string $templateCode = null;

    private ?int $moduleType = null;

    private ?Collection $config = null;

    private ?Collection $template = null;

    private ?Collection $emailSubjectData = null;

    private ?string $dynamicEmailSubject = null;

    private ?string $emailId = null;

    private ?string $mailableClass = null;

    private ?string $mailableDtoClass = null;

    private ?int $userId = null;

    private ?int $isSentRecieved = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->templateCode)) {
            $collection['template_code'] = $this->templateCode;
        }
        if (isset($this->moduleType)) {
            $collection['module_type'] = $this->moduleType;
        }
        if (isset($this->config)) {
            $collection['config'] = $this->config;
        }
        if (isset($this->template)) {
            $collection['template'] = $this->template;
        }
        if (isset($this->emailSubjectData)) {
            $collection['email_subject_data'] = $this->emailSubjectData;
        }
        if (isset($this->dynamicEmailSubject)) {
            $collection['dynamic_email_subject'] = $this->dynamicEmailSubject;
        }
        if (isset($this->emailId)) {
            $collection['email_id'] = $this->emailId;
        }
        if (isset($this->mailableClass)) {
            $collection['mailable_class'] = $this->mailableClass;
        }
        if (isset($this->mailableDtoClass)) {
            $collection['mailable_dto_class'] = $this->mailableDtoClass;
        }
        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->isSentRecieved)) {
            $collection['is_sent_recieved'] = $this->isSentRecieved;
        }

        return $collection;
    }

    public static function fromArray(array $data): self
    {
        $bo = new self;

        if (empty($data)) {
            return $bo;
        }

        isset($data['template_code']) && $bo->setTemplateCode($data['template_code']);
        isset($data['module_type']) && $bo->setModuleType($data['module_type']);
        isset($data['config']) && $bo->setConfig($data['config']);
        isset($data['template']) && $bo->setTemplate($data['template']);
        isset($data['email_subject_data']) && $bo->setEmailSubjectData($data['email_subject_data']);
        isset($data['dynamic_email_subject']) && $bo->setDynamicEmailSubject($data['dynamic_email_subject']);
        isset($data['email_id']) && $bo->setEmailId($data['email_id']);
        isset($data['mailable_class']) && $bo->setMailableClass($data['mailable_class']);
        isset($data['mailable_dto_class']) && $bo->setMailableDtoClass($data['mailable_dto_class']);
        isset($data['user_id']) && $bo->setUserId($data['user_id']);
        isset($data['is_sent_recieved']) && $bo->setIsSentRecieved($data['is_sent_recieved']);

        return $bo;
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
     * Get the value of config
     */
    public function getConfig(): ?Collection
    {
        return $this->config;
    }

    /**
     * Set the value of config
     */
    public function setConfig(?Collection $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the value of template
     */
    public function getTemplate(): ?Collection
    {
        return $this->template;
    }

    /**
     * Set the value of template
     */
    public function setTemplate(?Collection $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the value of emailSubjectData
     */
    public function getEmailSubjectData(): ?Collection
    {
        return $this->emailSubjectData;
    }

    /**
     * Set the value of emailSubjectData
     */
    public function setEmailSubjectData(?Collection $emailSubjectData): self
    {
        $this->emailSubjectData = $emailSubjectData;

        return $this;
    }

    /**
     * Get the value of dynamicEmailSubject
     */
    public function getDynamicEmailSubject(): ?string
    {
        return $this->dynamicEmailSubject;
    }

    /**
     * Set the value of dynamicEmailSubject
     */
    public function setDynamicEmailSubject(?string $dynamicEmailSubject): self
    {
        $this->dynamicEmailSubject = $dynamicEmailSubject;

        return $this;
    }

    /**
     * Get the value of emailId
     */
    public function getEmailId(): ?string
    {
        return $this->emailId;
    }

    /**
     * Set the value of emailId
     */
    public function setEmailId(?string $emailId): self
    {
        $this->emailId = $emailId;

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
     * Get the value of mailableDtoClass
     */
    public function getMailableDtoClass(): ?string
    {
        return $this->mailableDtoClass;
    }

    /**
     * Set the value of mailableDtoClass
     */
    public function setMailableDtoClass(?string $mailableDtoClass): self
    {
        $this->mailableDtoClass = $mailableDtoClass;

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
     * Get the value of isSentRecieved
     */
    public function getIsSentRecieved(): ?int
    {
        return $this->isSentRecieved;
    }

    /**
     * Set the value of isSentRecieved
     */
    public function setIsSentRecieved(?int $isSentRecieved): self
    {
        $this->isSentRecieved = $isSentRecieved;

        return $this;
    }
}
