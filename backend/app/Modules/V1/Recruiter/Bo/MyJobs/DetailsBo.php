<?php

namespace App\Modules\V1\Recruiter\Bo\MyJobs;

class DetailsBo
{
    private ?string $text = null;

    private int $page = 1;

    private int $perPage = 20;

    private string $sortBy = 'created_at';

    private string $sortOrder = 'desc';

    private ?string $status = null;

    private ?array $workMode = null;

    private ?array $jobType = null;

    private ?array $experienceLevel = null;

    private ?float $salaryMin = null;

    private ?float $salaryMax = null;

    private ?string $location = null;

    private ?int $jobCategoryId = null;

    public function toArray(): array
    {
        $collection = [
            'page' => $this->page,
            'per_page' => $this->perPage,
            'sort_by' => $this->sortBy,
            'sort_order' => $this->sortOrder,
        ];

        if (isset($this->text)) {
            $collection['text'] = $this->text;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
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
        if (isset($this->location)) {
            $collection['location'] = $this->location;
        }
        if (isset($this->jobCategoryId)) {
            $collection['job_category_id'] = $this->jobCategoryId;
        }

        return $collection;
    }

    /**
     * Get the value of text
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Set the value of text
     */
    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of page
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Set the value of page
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of perPage
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Set the value of perPage
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Get the value of sortBy
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * Set the value of sortBy
     */
    public function setSortBy(string $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * Get the value of sortOrder
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * Set the value of sortOrder
     */
    public function setSortOrder(string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

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
     * Get the value of workMode
     */
    public function getWorkMode(): ?array
    {
        return $this->workMode;
    }

    /**
     * Set the value of workMode
     */
    public function setWorkMode(?array $workMode): self
    {
        $this->workMode = $workMode;

        return $this;
    }

    /**
     * Get the value of jobType
     */
    public function getJobType(): ?array
    {
        return $this->jobType;
    }

    /**
     * Set the value of jobType
     */
    public function setJobType(?array $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    /**
     * Get the value of experienceLevel
     */
    public function getExperienceLevel(): ?array
    {
        return $this->experienceLevel;
    }

    /**
     * Set the value of experienceLevel
     */
    public function setExperienceLevel(?array $experienceLevel): self
    {
        $this->experienceLevel = $experienceLevel;

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
}
