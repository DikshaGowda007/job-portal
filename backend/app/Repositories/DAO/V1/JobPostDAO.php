<?php

namespace App\Repositories\DAO\V1;

class JobPostDAO
{
    private ?int $id = null;
    private ?int $userId = null;
    private ?int $modifiedByUserId = null;
    private ?string $companyName = null;
    private ?string $title = null;
    private ?string $jobDescription = null;
    private ?string $location = null;
    private ?float $salary = null;
    private ?float $salaryMin = null;
    private ?float $salaryMax = null;
    private ?string $salaryCurrency = null;
    private ?string $salaryType = null;
    private ?int $jobCategoryId = null;
    private ?string $workMode = null;
    private ?string $jobType = null;
    private ?string $rolesResponsibility = null;
    private ?string $experienceLevel = null;
    private ?int $experienceMin = null;
    private ?int $experienceMax = null;
    private ?string $education = null;
    private ?string $skills = null;
    private ?string $status = null;
    private ?string $expiresAt = null;
    private ?int $openingsCount = null;
    private ?string $createdAt = null;
    private ?string $updatedAt = null;
    private ?int $isDeleted = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->modifiedByUserId)) {
            $collection['modified_by_user_id'] = $this->modifiedByUserId;
        }
        if (isset($this->companyName)) {
            $collection['company_name'] = $this->companyName;
        }
        if (isset($this->title)) {
            $collection['title'] = $this->title;
        }
        if (isset($this->jobDescription)) {
            $collection['job_description'] = $this->jobDescription;
        }
        if (isset($this->location)) {
            $collection['location'] = $this->location;
        }
        if (isset($this->salary)) {
            $collection['salary'] = $this->salary;
        }
        if (isset($this->salaryMin)) {
            $collection['salary_min'] = $this->salaryMin;
        }
        if (isset($this->salaryMax)) {
            $collection['salary_max'] = $this->salaryMax;
        }
        if (isset($this->salaryCurrency)) {
            $collection['salary_currency'] = $this->salaryCurrency;
        }
        if (isset($this->salaryType)) {
            $collection['salary_type'] = $this->salaryType;
        }
        if (isset($this->jobCategoryId)) {
            $collection['job_category_id'] = $this->jobCategoryId;
        }
        if (isset($this->workMode)) {
            $collection['work_mode'] = $this->workMode;
        }
        if (isset($this->jobType)) {
            $collection['job_type'] = $this->jobType;
        }
        if (isset($this->rolesResponsibility)) {
            $collection['roles_responsibility'] = $this->rolesResponsibility;
        }
        if (isset($this->experienceLevel)) {
            $collection['experience_level'] = $this->experienceLevel;
        }
        if (isset($this->experienceMin)) {
            $collection['experience_min'] = $this->experienceMin;
        }
        if (isset($this->experienceMax)) {
            $collection['experience_max'] = $this->experienceMax;
        }
        if (isset($this->education)) {
            $collection['education'] = $this->education;
        }
        if (isset($this->skills)) {
            $collection['skills'] = $this->skills;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->expiresAt)) {
            $collection['expires_at'] = $this->expiresAt;
        }
        if (isset($this->openingsCount)) {
            $collection['openings_count'] = $this->openingsCount;
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
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Get the value of modifiedByUserId
     */
    public function getModifiedByUserId(): ?int
    {
        return $this->modifiedByUserId;
    }

    /**
     * Set the value of modifiedByUserId
     */
    public function setModifiedByUserId(?int $modifiedByUserId): self
    {
        $this->modifiedByUserId = $modifiedByUserId;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the value of companyName
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     */
    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of jobDescription
     */
    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    /**
     * Set the value of jobDescription
     */
    public function setJobDescription(?string $jobDescription): self
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    /**
     * Get the value of location
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Set the value of location
     */
    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of salary
     */
    public function getSalary(): ?float
    {
        return $this->salary;
    }

    /**
     * Set the value of salary
     */
    public function setSalary(?float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get the value of salaryMin
     */
    public function getSalaryMin(): ?float
    {
        return $this->salaryMin;
    }

    /**
     * Set the value of salaryMin
     */
    public function setSalaryMin(?float $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    /**
     * Get the value of salaryMax
     */
    public function getSalaryMax(): ?float
    {
        return $this->salaryMax;
    }

    /**
     * Set the value of salaryMax
     */
    public function setSalaryMax(?float $salaryMax): self
    {
        $this->salaryMax = $salaryMax;

        return $this;
    }

    /**
     * Get the value of salaryCurrency
     */
    public function getSalaryCurrency(): ?string
    {
        return $this->salaryCurrency;
    }

    /**
     * Set the value of salaryCurrency
     */
    public function setSalaryCurrency(?string $salaryCurrency): self
    {
        $this->salaryCurrency = $salaryCurrency;

        return $this;
    }

    /**
     * Get the value of salaryType
     */
    public function getSalaryType(): ?string
    {
        return $this->salaryType;
    }

    /**
     * Set the value of salaryType
     */
    public function setSalaryType(?string $salaryType): self
    {
        $this->salaryType = $salaryType;

        return $this;
    }

    /**
     * Get the value of jobCategoryId
     */
    public function getJobCategoryId(): ?int
    {
        return $this->jobCategoryId;
    }

    /**
     * Set the value of jobCategoryId
     */
    public function setJobCategoryId(?int $jobCategoryId): self
    {
        $this->jobCategoryId = $jobCategoryId;

        return $this;
    }

    /**
     * Get the value of workMode
     */
    public function getWorkMode(): ?string
    {
        return $this->workMode;
    }

    /**
     * Set the value of workMode
     */
    public function setWorkMode(?string $workMode): self
    {
        $this->workMode = $workMode;

        return $this;
    }

    /**
     * Get the value of jobType
     */
    public function getJobType(): ?string
    {
        return $this->jobType;
    }

    /**
     * Set the value of jobType
     */
    public function setJobType(?string $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    /**
     * Get the value of rolesResponsibility
     */
    public function getRolesResponsibility(): ?string
    {
        return $this->rolesResponsibility;
    }

    /**
     * Set the value of rolesResponsibility
     */
    public function setRolesResponsibility(?string $rolesResponsibility): self
    {
        $this->rolesResponsibility = $rolesResponsibility;

        return $this;
    }

    /**
     * Get the value of experienceLevel
     */
    public function getExperienceLevel(): ?string
    {
        return $this->experienceLevel;
    }

    /**
     * Set the value of experienceLevel
     */
    public function setExperienceLevel(?string $experienceLevel): self
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    /**
     * Get the value of experienceMin
     */
    public function getExperienceMin(): ?int
    {
        return $this->experienceMin;
    }

    /**
     * Set the value of experienceMin
     */
    public function setExperienceMin(?int $experienceMin): self
    {
        $this->experienceMin = $experienceMin;

        return $this;
    }

    /**
     * Get the value of experienceMax
     */
    public function getExperienceMax(): ?int
    {
        return $this->experienceMax;
    }

    /**
     * Set the value of experienceMax
     */
    public function setExperienceMax(?int $experienceMax): self
    {
        $this->experienceMax = $experienceMax;

        return $this;
    }

    /**
     * Get the value of education
     */
    public function getEducation(): ?string
    {
        return $this->education;
    }

    /**
     * Set the value of education
     */
    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get the value of skills
     */
    public function getSkills(): ?string
    {
        return $this->skills;
    }

    /**
     * Set the value of skills
     */
    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;

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
     * Get the value of expiresAt
     */
    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
    }

    /**
     * Set the value of expiresAt
     */
    public function setExpiresAt(?string $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get the value of openingsCount
     */
    public function getOpeningsCount(): ?int
    {
        return $this->openingsCount;
    }

    /**
     * Set the value of openingsCount
     */
    public function setOpeningsCount(?int $openingsCount): self
    {
        $this->openingsCount = $openingsCount;

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