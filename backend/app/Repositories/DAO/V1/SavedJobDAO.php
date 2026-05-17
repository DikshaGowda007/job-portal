<?php

namespace App\Repositories\DAO\V1;

class SavedJobDAO
{
    private ?int $userId = null;

    private ?int $jobPostId = null;

    private ?string $createdAt = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->jobPostId)) {
            $collection['job_post_id'] = $this->jobPostId;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }

        return $collection;
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
     * Get the value of jobPostId
     */
    public function getJobPostId(): ?int
    {
        return $this->jobPostId;
    }

    /**
     * Set the value of jobPostId
     */
    public function setJobPostId(?int $jobPostId): self
    {
        $this->jobPostId = $jobPostId;

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
}
