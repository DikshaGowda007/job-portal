<?php

namespace App\Repositories\DAO\V1;

class JobAlertDAO
{
    private ?int $userId = null;

    private ?string $keyword = null;

    private ?string $location = null;

    private ?int $jobCategoryId = null;

    private ?string $jobType = null;

    private ?string $workMode = null;

    private ?string $experienceLevel = null;

    private ?int $isActive = null;

    private ?int $isDeleted = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    public function toArray(): array
    {
        $data = [];

        if (isset($this->userId)) {
            $data['user_id'] = $this->userId;
        }
        if (isset($this->keyword)) {
            $data['keyword'] = $this->keyword;
        }
        if (isset($this->location)) {
            $data['location'] = $this->location;
        }
        if (isset($this->jobCategoryId)) {
            $data['job_category_id'] = $this->jobCategoryId;
        }
        if (isset($this->jobType)) {
            $data['job_type'] = $this->jobType;
        }
        if (isset($this->workMode)) {
            $data['work_mode'] = $this->workMode;
        }
        if (isset($this->experienceLevel)) {
            $data['experience_level'] = $this->experienceLevel;
        }
        if (isset($this->isActive)) {
            $data['is_active'] = $this->isActive;
        }
        if (isset($this->isDeleted)) {
            $data['is_deleted'] = $this->isDeleted;
        }
        if (isset($this->createdAt)) {
            $data['created_at'] = $this->createdAt;
        }
        if (isset($this->updatedAt)) {
            $data['updated_at'] = $this->updatedAt;
        }

        return $data;
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

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getJobCategoryId(): ?int
    {
        return $this->jobCategoryId;
    }

    public function setJobCategoryId(?int $jobCategoryId): self
    {
        $this->jobCategoryId = $jobCategoryId;

        return $this;
    }

    public function getJobType(): ?string
    {
        return $this->jobType;
    }

    public function setJobType(?string $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    public function getWorkMode(): ?string
    {
        return $this->workMode;
    }

    public function setWorkMode(?string $workMode): self
    {
        $this->workMode = $workMode;

        return $this;
    }

    public function getExperienceLevel(): ?string
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(?string $experienceLevel): self
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    public function setIsActive(?int $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
