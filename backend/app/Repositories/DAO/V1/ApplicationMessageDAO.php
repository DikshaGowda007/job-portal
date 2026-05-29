<?php

namespace App\Repositories\DAO\V1;

class ApplicationMessageDAO
{
    private ?int $applicationId = null;

    private ?int $senderId = null;

    private ?string $message = null;

    private ?string $createdAt = null;

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
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }

        return $collection;
    }
}
