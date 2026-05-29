<?php

namespace App\Repositories\DAO\V1;

class JobApplicationDAO
{
    private ?int $userId = null;

    private ?int $jobPostId = null;

    private ?string $resumePath = null;

    private ?string $coverLetter = null;

    private ?float $expectedSalary = null;

    private ?string $expectedSalaryCurrency = null;

    private ?int $noticePeriod = null;

    private ?int $experienceYears = null;

    private ?string $status = null;

    private ?string $recruiterNotes = null;

    private ?int $reviewedByUserId = null;

    private ?string $reviewedAt = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    private ?int $isDeleted = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->jobPostId)) {
            $collection['job_post_id'] = $this->jobPostId;
        }
        if (isset($this->resumePath)) {
            $collection['resume_path'] = $this->resumePath;
        }
        if (isset($this->coverLetter)) {
            $collection['cover_letter'] = $this->coverLetter;
        }
        if (isset($this->expectedSalary)) {
            $collection['expected_salary'] = $this->expectedSalary;
        }
        if (isset($this->expectedSalaryCurrency)) {
            $collection['expected_salary_currency'] = $this->expectedSalaryCurrency;
        }
        if (isset($this->noticePeriod)) {
            $collection['notice_period'] = $this->noticePeriod;
        }
        if (isset($this->experienceYears)) {
            $collection['experience_years'] = $this->experienceYears;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->recruiterNotes)) {
            $collection['recruiter_notes'] = $this->recruiterNotes;
        }
        if (isset($this->reviewedByUserId)) {
            $collection['reviewed_by_user_id'] = $this->reviewedByUserId;
        }
        if (isset($this->reviewedAt)) {
            $collection['reviewed_at'] = $this->reviewedAt;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }
        if (isset($this->updatedAt)) {
            $collection['updated_at'] = $this->updatedAt;
        }
        if (isset($this->isDeleted)) {
            $collection['is_deleted'] = $this->isDeleted;
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
     * Get the value of resumePath
     */
    public function getResumePath(): ?string
    {
        return $this->resumePath;
    }

    /**
     * Set the value of resumePath
     */
    public function setResumePath(?string $resumePath): self
    {
        $this->resumePath = $resumePath;

        return $this;
    }

    /**
     * Get the value of coverLetter
     */
    public function getCoverLetter(): ?string
    {
        return $this->coverLetter;
    }

    /**
     * Set the value of coverLetter
     */
    public function setCoverLetter(?string $coverLetter): self
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }

    /**
     * Get the value of expectedSalary
     */
    public function getExpectedSalary(): ?float
    {
        return $this->expectedSalary;
    }

    /**
     * Set the value of expectedSalary
     */
    public function setExpectedSalary(?float $expectedSalary): self
    {
        $this->expectedSalary = $expectedSalary;

        return $this;
    }

    /**
     * Get the value of expectedSalaryCurrency
     */
    public function getExpectedSalaryCurrency(): ?string
    {
        return $this->expectedSalaryCurrency;
    }

    /**
     * Set the value of expectedSalaryCurrency
     */
    public function setExpectedSalaryCurrency(?string $expectedSalaryCurrency): self
    {
        $this->expectedSalaryCurrency = $expectedSalaryCurrency;

        return $this;
    }

    /**
     * Get the value of noticePeriod
     */
    public function getNoticePeriod(): ?int
    {
        return $this->noticePeriod;
    }

    /**
     * Set the value of noticePeriod
     */
    public function setNoticePeriod(?int $noticePeriod): self
    {
        $this->noticePeriod = $noticePeriod;

        return $this;
    }

    /**
     * Get the value of experienceYears
     */
    public function getExperienceYears(): ?int
    {
        return $this->experienceYears;
    }

    /**
     * Set the value of experienceYears
     */
    public function setExperienceYears(?int $experienceYears): self
    {
        $this->experienceYears = $experienceYears;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of recruiterNotes
     */
    public function getRecruiterNotes(): ?string
    {
        return $this->recruiterNotes;
    }

    /**
     * Set the value of recruiterNotes
     */
    public function setRecruiterNotes(?string $recruiterNotes): self
    {
        $this->recruiterNotes = $recruiterNotes;

        return $this;
    }

    /**
     * Get the value of reviewedByUserId
     */
    public function getReviewedByUserId(): ?int
    {
        return $this->reviewedByUserId;
    }

    /**
     * Set the value of reviewedByUserId
     */
    public function setReviewedByUserId(?int $reviewedByUserId): self
    {
        $this->reviewedByUserId = $reviewedByUserId;

        return $this;
    }

    /**
     * Get the value of reviewedAt
     */
    public function getReviewedAt(): ?string
    {
        return $this->reviewedAt;
    }

    /**
     * Set the value of reviewedAt
     */
    public function setReviewedAt(?string $reviewedAt): self
    {
        $this->reviewedAt = $reviewedAt;

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
