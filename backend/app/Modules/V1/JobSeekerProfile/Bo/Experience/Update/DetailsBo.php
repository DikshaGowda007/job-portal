<?php

namespace App\Modules\V1\JobSeekerProfile\Bo\Experience\Update;

class DetailsBo
{
    private ?int $experienceId = null;

    private ?string $jobTitle = null;

    private ?string $companyName = null;

    private ?string $employmentType = null;

    private ?string $location = null;

    private ?string $workMode = null;

    private ?string $startDate = null;

    private ?string $endDate = null;

    private ?bool $isCurrent = null;

    private ?string $description = null;

    public function getExperienceId(): ?int
    {
        return $this->experienceId;
    }

    public function setExperienceId(?int $experienceId): self
    {
        $this->experienceId = $experienceId;

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
}
