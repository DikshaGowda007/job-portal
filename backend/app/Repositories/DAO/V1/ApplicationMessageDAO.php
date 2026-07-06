<?php

namespace App\Repositories\DAO\V1;

class ApplicationMessageDAO
{
    private ?int $applicationId = null;

    private ?int $senderId = null;

    private ?string $message = null;

    private ?string $readAt = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    private ?int $isDeleted = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->applicationId)) {
            $collection['application_id'] = $this->applicationId;
        }
        if (isset($this->senderId)) {
            $collection['sender_id'] = $this->senderId;
        }
        if (isset($this->message)) {
            $collection['message'] = $this->message;
        }
        if (isset($this->readAt)) {
            $collection['read_at'] = $this->readAt;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }
        if (isset($this->updated_at)) {
            $collection['updated_at'] = $this->updated_at;
        }
        if (isset($this->is_deleted)) {
            $collection['is_deleted'] = $this->is_deleted;
        }

        return $collection;
    }

    /**
     * Get the value of applicationId
     */
    public function getApplicationId(): ?int
    {
        return $this->applicationId;
    }

    /**
     * Set the value of applicationId
     */
    public function setApplicationId(?int $applicationId): self
    {
        $this->applicationId = $applicationId;

        return $this;
    }

    /**
     * Get the value of senderId
     */
    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    /**
     * Set the value of senderId
     */
    public function setSenderId(?int $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the value of message
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of readAt
     */
    public function getReadAt(): ?string
    {
        return $this->readAt;
    }

    /**
     * Set the value of readAt
     */
    public function setReadAt(?string $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of isDeleted
     */
    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    /**
     * Set the value of isDeleted
     */
    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
