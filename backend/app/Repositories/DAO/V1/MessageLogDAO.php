<?php

namespace App\Repositories\DAO\V1;

class MessageLogDAO
{
    private ?int $applicationId = null;

    private ?int $senderId = null;

    private ?int $receiverId = null;

    private ?string $message = null;

    private ?int $isDelivered = null;

    private ?int $status = null;

    private ?int $isDeleted = null;

    public function toArray(): array
    {
        $data = [];

        if (isset($this->applicationId)) {
            $data['application_id'] = $this->applicationId;
        }
        if (isset($this->senderId)) {
            $data['sender_id'] = $this->senderId;
        }
        if (isset($this->receiverId)) {
            $data['receiver_id'] = $this->receiverId;
        }
        if (isset($this->message)) {
            $data['message'] = $this->message;
        }
        if (isset($this->isDelivered)) {
            $data['is_delivered'] = $this->isDelivered;
        }
        if (isset($this->status)) {
            $data['status'] = $this->status;
        }
        if (isset($this->isDeleted)) {
            $data['is_deleted'] = $this->isDeleted;
        }

        return $data;
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
     * Get the value of receiverId
     */
    public function getReceiverId(): ?int
    {
        return $this->receiverId;
    }

    /**
     * Set the value of receiverId
     */
    public function setReceiverId(?int $receiverId): self
    {
        $this->receiverId = $receiverId;

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
     * Get the value of isDelivered
     */
    public function getIsDelivered(): ?int
    {
        return $this->isDelivered;
    }

    /**
     * Set the value of isDelivered
     */
    public function setIsDelivered(?int $isDelivered): self
    {
        $this->isDelivered = $isDelivered;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;

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
