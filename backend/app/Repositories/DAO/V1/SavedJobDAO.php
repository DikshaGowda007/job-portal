<?php

namespace App\Repositories\DAO\V1;

class SavedJobDAO
{
    private ?int $userId = null;

    private ?int $jobPostId = null;

    private ?int $isDeleted = null;

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
        if (isset($this->isDeleted)) {
            $collection['is_deleted'] = $this->isDeleted;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }

        return $collection;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getJobPostId(): ?int
    {
        return $this->jobPostId;
    }

    public function setJobPostId(?int $jobPostId): self
    {
        $this->jobPostId = $jobPostId;

        return $this;
    }

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
