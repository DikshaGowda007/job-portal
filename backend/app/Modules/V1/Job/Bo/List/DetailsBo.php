<?php

namespace App\Modules\V1\Job\Bo\List;

class DetailsBo
{
    private ?string $text = null;

    private int $page = 1;

    private int $perPage = 20;

    private string $sortBy = 'created_at';

    private string $sortOrder = 'desc';

    private ?array $workMode = null;

    private ?array $jobType = null;

    private ?array $experienceLevel = null;

    private ?float $salaryMin = null;

    private ?float $salaryMax = null;

    private ?float $experienceMin = null;

    private ?float $experienceMax = null;

    private ?string $location = null;

    private ?int $jobCategoryId = null;

    private ?array $skills = null;

    private string $status = 'open';

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function setSortBy(string $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    public function setSortOrder(string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getWorkMode(): ?array
    {
        return $this->workMode;
    }

    public function setWorkMode(?array $workMode): self
    {
        $this->workMode = $workMode;

        return $this;
    }

    public function getJobType(): ?array
    {
        return $this->jobType;
    }

    public function setJobType(?array $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    public function getExperienceLevel(): ?array
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(?array $experienceLevel): self
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    public function getSalaryMin(): ?float
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(?float $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    public function getSalaryMax(): ?float
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(?float $salaryMax): self
    {
        $this->salaryMax = $salaryMax;

        return $this;
    }

    public function getExperienceMin(): ?float
    {
        return $this->experienceMin;
    }

    public function setExperienceMin(?float $experienceMin): self
    {
        $this->experienceMin = $experienceMin;

        return $this;
    }

    public function getExperienceMax(): ?float
    {
        return $this->experienceMax;
    }

    public function setExperienceMax(?float $experienceMax): self
    {
        $this->experienceMax = $experienceMax;

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

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(?array $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function toArray(): array
    {
        $collection = [
            'page' => $this->page,
            'per_page' => $this->perPage,
            'sort_by' => $this->sortBy,
            'sort_order' => $this->sortOrder,
            'status' => $this->status,
        ];

        if (isset($this->text)) {
            $collection['text'] = $this->text;
        }
        if (isset($this->workMode)) {
            $collection['work_mode'] = $this->workMode;
        }
        if (isset($this->jobType)) {
            $collection['job_type'] = $this->jobType;
        }
        if (isset($this->experienceLevel)) {
            $collection['experience_level'] = $this->experienceLevel;
        }
        if (isset($this->salaryMin)) {
            $collection['salary_min'] = $this->salaryMin;
        }
        if (isset($this->salaryMax)) {
            $collection['salary_max'] = $this->salaryMax;
        }
        if (isset($this->experienceMin)) {
            $collection['experience_min'] = $this->experienceMin;
        }
        if (isset($this->experienceMax)) {
            $collection['experience_max'] = $this->experienceMax;
        }
        if (isset($this->location)) {
            $collection['location'] = $this->location;
        }
        if (isset($this->jobCategoryId)) {
            $collection['job_category_id'] = $this->jobCategoryId;
        }
        if (isset($this->skills)) {
            $collection['skills'] = $this->skills;
        }

        return $collection;
    }
}
