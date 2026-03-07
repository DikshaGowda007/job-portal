<?php

namespace App\Modules\V1\JobApplication\Bo\Apply;

class DetailsBo
{
    private ?int $userId = null;

    private ?int $jobPostId = null;

    private ?string $resumePath = null;

    private ?string $coverLetter = null;

    private ?float $expectedSalary = null;

    private ?string $expectedSalaryCurrency = null;

    private ?string $noticePeriod = null;

    private ?int $experienceYears = null;

    public function toArray(): array
    {
        $data = [];

        if (isset($this->userId)) {
            $data['user_id'] = $this->userId;
        }

        if (isset($this->jobPostId)) {
            $data['job_post_id'] = $this->jobPostId;
        }

        if (isset($this->resumePath)) {
            $data['resume_path'] = $this->resumePath;
        }

        if (isset($this->coverLetter)) {
            $data['cover_letter'] = $this->coverLetter;
        }

        if (isset($this->jobDescription)) {
            $data['job_description'] = $this->jobDescription;
        }

        if (isset($this->location)) {
            $data['location'] = $this->location;
        }

        if (isset($this->salary)) {
            $data['salary'] = $this->salary;
        }

        if (isset($this->salaryMin)) {
            $data['salary_min'] = $this->salaryMin;
        }

        if (isset($this->salaryMax)) {
            $data['salary_max'] = $this->salaryMax;
        }

        if (isset($this->salaryCurrency)) {
            $data['salary_currency'] = $this->salaryCurrency;
        }

        if (isset($this->salaryType)) {
            $data['salary_type'] = $this->salaryType;
        }

        if (isset($this->jobCategoryId)) {
            $data['job_category_id'] = $this->jobCategoryId;
        }

        if (isset($this->workMode)) {
            $data['work_mode'] = $this->workMode;
        }

        if (isset($this->jobType)) {
            $data['job_type'] = $this->jobType;
        }

        if (isset($this->rolesResponsibility)) {
            $data['roles_responsibility'] = $this->rolesResponsibility;
        }

        if (isset($this->experienceLevel)) {
            $data['experience_level'] = $this->experienceLevel;
        }

        if (isset($this->experienceMin)) {
            $data['experience_min'] = $this->experienceMin;
        }

        if (isset($this->experienceMax)) {
            $data['experience_max'] = $this->experienceMax;
        }

        if (isset($this->education)) {
            $data['education'] = $this->education;
        }

        if (isset($this->skills)) {
            $data['skills'] = $this->skills;
        }

        if (isset($this->status)) {
            $data['status'] = $this->status;
        }

        if (isset($this->expiresAt)) {
            $data['expires_at'] = $this->expiresAt;
        }

        if (isset($this->openingsCount)) {
            $data['openings_count'] = $this->openingsCount;
        }

        return $data;
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
    public function getNoticePeriod(): ?string
    {
        return $this->noticePeriod;
    }

    /**
     * Set the value of noticePeriod
     */
    public function setNoticePeriod(?string $noticePeriod): self
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
}
