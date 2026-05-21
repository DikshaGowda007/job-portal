<?php

namespace App\Repositories\DAO\V1;

class JobSeekerExperienceDAO
{
    private ?int $id = null;

    private ?int $jobSeekerProfileId = null;

    private ?string $jobTitle = null;

    private ?string $companyName = null;

    private ?string $employmentType = null;

    private ?string $location = null;

    private ?string $workMode = null;

    private ?string $startDate = null;

    private ?string $endDate = null;

    private ?bool $isCurrent = null;

    private ?string $description = null;

    private ?array $responsibilities = null;

    private ?array $achievements = null;

    private ?array $skillsUsed = null;

    private ?int $isDeleted = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->jobSeekerProfileId)) {
            $collection['job_seeker_profile_id'] = $this->jobSeekerProfileId;
        }
        if (isset($this->jobTitle)) {
            $collection['job_title'] = $this->jobTitle;
        }
        if (isset($this->companyName)) {
            $collection['company_name'] = $this->companyName;
        }
        if (isset($this->employmentType)) {
            $collection['employment_type'] = $this->employmentType;
        }
        if (isset($this->location)) {
            $collection['location'] = $this->location;
        }
        if (isset($this->workMode)) {
            $collection['work_mode'] = $this->workMode;
        }
        if (isset($this->startDate)) {
            $collection['start_date'] = $this->startDate;
        }
        if ($this->endDate !== null) {
            $collection['end_date'] = $this->endDate;
        }
        if (isset($this->isCurrent)) {
            $collection['is_current'] = $this->isCurrent;
        }
        if (isset($this->description)) {
            $collection['description'] = $this->description;
        }
        if (isset($this->responsibilities)) {
            $collection['responsibilities'] = $this->responsibilities;
        }
        if (isset($this->achievements)) {
            $collection['achievements'] = $this->achievements;
        }
        if (isset($this->skillsUsed)) {
            $collection['skills_used'] = $this->skillsUsed;
        }
        if (isset($this->isDeleted)) {
            $collection['is_deleted'] = $this->isDeleted;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }
        if (isset($this->updatedAt)) {
            $collection['updated_at'] = $this->updatedAt;
        }

        return $collection;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getJobSeekerProfileId(): ?int
    {
        return $this->jobSeekerProfileId;
    }

    public function setJobSeekerProfileId(?int $jobSeekerProfileId): self
    {
        $this->jobSeekerProfileId = $jobSeekerProfileId;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getEmploymentType(): ?string
    {
        return $this->employmentType;
    }

    public function setEmploymentType(?string $employmentType): self
    {
        $this->employmentType = $employmentType;

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

    public function getWorkMode(): ?string
    {
        return $this->workMode;
    }

    public function setWorkMode(?string $workMode): self
    {
        $this->workMode = $workMode;

        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(?bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getResponsibilities(): ?array
    {
        return $this->responsibilities;
    }

    public function setResponsibilities(?array $responsibilities): self
    {
        $this->responsibilities = $responsibilities;

        return $this;
    }

    public function getAchievements(): ?array
    {
        return $this->achievements;
    }

    public function setAchievements(?array $achievements): self
    {
        $this->achievements = $achievements;

        return $this;
    }

    public function getSkillsUsed(): ?array
    {
        return $this->skillsUsed;
    }

    public function setSkillsUsed(?array $skillsUsed): self
    {
        $this->skillsUsed = $skillsUsed;

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
