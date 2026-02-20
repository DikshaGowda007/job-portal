<?php

namespace App\Modules\V1\Job\Bo\Edit;

class DetailsBo
{
    private ?int $id = null;
    private ?int $userId = null;
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
    private ?int $modifiedByUserId = null;
    
    public function toArray(): array
    {
        $data = [];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        if ($this->userId !== null) {
            $data['user_id'] = $this->userId;
        }
        if ($this->companyName !== null) {
            $data['company_name'] = $this->companyName;
        }
        if ($this->title !== null) {
            $data['title'] = $this->title;
        }
        if ($this->jobDescription !== null) {
            $data['job_description'] = $this->jobDescription;
        }
        if ($this->location !== null) {
            $data['location'] = $this->location;
        }
        if ($this->salary !== null) {
            $data['salary'] = $this->salary;
        }
        if ($this->salaryMin !== null) {
            $data['salary_min'] = $this->salaryMin;
        }
        if ($this->salaryMax !== null) {
            $data['salary_max'] = $this->salaryMax;
        }
        if ($this->salaryCurrency !== null) {
            $data['salary_currency'] = $this->salaryCurrency;
        }
        if ($this->salaryType !== null) {
            $data['salary_type'] = $this->salaryType;
        }
        if ($this->jobCategoryId !== null) {
            $data['job_category_id'] = $this->jobCategoryId;
        }
        if ($this->workMode !== null) {
            $data['work_mode'] = $this->workMode;
        }
        if ($this->jobType !== null) {
            $data['job_type'] = $this->jobType;
        }
        if ($this->rolesResponsibility !== null) {
            $data['roles_responsibility'] = $this->rolesResponsibility;
        }
        if ($this->experienceLevel !== null) {
            $data['experience_level'] = $this->experienceLevel;
        }
        if ($this->experienceMin !== null) {
            $data['experience_min'] = $this->experienceMin;
        }
        if ($this->experienceMax !== null) {
            $data['experience_max'] = $this->experienceMax;
        }
        if ($this->education !== null) {
            $data['education'] = $this->education;
        }
        if ($this->skills !== null) {
            $data['skills'] = $this->skills;
        }
        if ($this->status !== null) {
            $data['status'] = $this->status;
        }
        if ($this->expiresAt !== null) {
            $data['expires_at'] = $this->expiresAt;
        }
        if ($this->openingsCount !== null) {
            $data['openings_count'] = $this->openingsCount;
        }
        if ($this->modifiedByUserId !== null) {
            $data['modified_by_userId'] = $this->modifiedByUserId;
        }

        return $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    public function setJobDescription(?string $jobDescription): void
    {
        $this->jobDescription = $jobDescription;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): void
    {
        $this->salary = $salary;
    }

    public function getSalaryMin(): ?float
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(?float $salaryMin): void
    {
        $this->salaryMin = $salaryMin;
    }

    public function getSalaryMax(): ?float
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(?float $salaryMax): void
    {
        $this->salaryMax = $salaryMax;
    }

    public function getSalaryCurrency(): ?string
    {
        return $this->salaryCurrency;
    }

    public function setSalaryCurrency(?string $salaryCurrency): void
    {
        $this->salaryCurrency = $salaryCurrency;
    }

    public function getSalaryType(): ?string
    {
        return $this->salaryType;
    }

    public function setSalaryType(?string $salaryType): void
    {
        $this->salaryType = $salaryType;
    }

    public function getJobCategoryId(): ?int
    {
        return $this->jobCategoryId;
    }

    public function setJobCategoryId(?int $jobCategoryId): void
    {
        $this->jobCategoryId = $jobCategoryId;
    }

    public function getWorkMode(): ?string
    {
        return $this->workMode;
    }

    public function setWorkMode(?string $workMode): void
    {
        $this->workMode = $workMode;
    }

    public function getJobType(): ?string
    {
        return $this->jobType;
    }

    public function setJobType(?string $jobType): void
    {
        $this->jobType = $jobType;
    }

    public function getRolesResponsibility(): ?string
    {
        return $this->rolesResponsibility;
    }

    public function setRolesResponsibility(?string $rolesResponsibility): void
    {
        $this->rolesResponsibility = $rolesResponsibility;
    }

    public function getExperienceLevel(): ?string
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(?string $experienceLevel): void
    {
        $this->experienceLevel = $experienceLevel;
    }

    public function getExperienceMin(): ?int
    {
        return $this->experienceMin;
    }

    public function setExperienceMin(?int $experienceMin): void
    {
        $this->experienceMin = $experienceMin;
    }

    public function getExperienceMax(): ?int
    {
        return $this->experienceMax;
    }

    public function setExperienceMax(?int $experienceMax): void
    {
        $this->experienceMax = $experienceMax;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(?string $education): void
    {
        $this->education = $education;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(?string $skills): void
    {
        $this->skills = $skills;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?string $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getOpeningsCount(): ?int
    {
        return $this->openingsCount;
    }

    public function setOpeningsCount(?int $openingsCount): void
    {
        $this->openingsCount = $openingsCount;
    }

    public function getModifiedByUserId(): ?int
    {
        return $this->modifiedByUserId;
    }

    public function setModifiedByUserId(?int $modifiedByUserId): self
    {
        $this->modifiedByUserId = $modifiedByUserId;

        return $this;
    }
}
