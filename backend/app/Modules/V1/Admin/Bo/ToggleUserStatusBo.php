<?php

namespace App\Modules\V1\Admin\Bo;

class ToggleUserStatusBo
{
    private int $userId;

    private string $status;

    private ?string $reason;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->reason)) {
            $collection['reason'] = $this->reason;
        }

        return $collection;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of reason
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * Set the value of reason
     */
    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}
