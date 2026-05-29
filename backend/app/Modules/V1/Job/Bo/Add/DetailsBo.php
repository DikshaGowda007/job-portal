<?php

namespace App\Modules\V1\Job\Bo\Add;

class DetailsBo
{
    private ?int $userId = null;

    // Basic job info
    private ?string $companyName = null;

    private ?string $title = null;

    private ?string $jobDescription = null;

    private ?string $location = null;

    // Salary
    private ?float $salary = null;

    private ?float $salaryMin = null;

    private ?float $salaryMax = null;

    private ?string $salaryCurrency = null;

    private ?string $salaryType = null;

    // Category
    private ?int $jobCategoryId = null;

    // Job type & work mode
    private ?string $workMode = null;

    private ?string $jobType = null;

    // Roles & responsibilities
    private ?string $rolesResponsibility = null;

    // Experience & education
    private ?string $experienceLevel = null;

    private ?int $experienceMin = null;

    private ?int $experienceMax = null;

    private ?string $education = null;

    // Skills
    private ?string $skills = null;

    // Job lifecycle
    private ?string $status = null;

    private ?string $expiresAt = null;

    // Openings
    private ?int $openingsCount = null;

    public function toArray(): array
    {
        $data = [];

        if (isset($this->userId)) {
            $data['user_id'] = $this->userId;
        }

        if (isset($this->companyName)) {
            $data['company_name'] = $this->companyName;
        }

        if (isset($this->title)) {
            $data['title'] = $this->title;
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
}
