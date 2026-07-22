<?php

namespace App\Modules\V1\Recruiter\Bo\CandidateSearch;

class DetailsBo
{
    private ?string $text = null;

    private ?array $skills = null;

    private ?string $location = null;

    private ?float $experienceMin = null;

    private ?float $experienceMax = null;

    private ?string $currentJobTitle = null;

    private ?array $workMode = null;

    private ?array $jobType = null;

    private ?string $noticePeriod = null;

    private ?bool $immediateJoiner = null;

    private int $page = 1;

    private int $perPage = 20;

    private string $sortBy = 'updated_at';

    private string $sortOrder = 'desc';

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
        if (isset($this->skills)) {
            $collection['skills'] = $this->skills;
        }
        if (isset($this->location)) {
            $collection['location'] = $this->location;
        }
        if (isset($this->experienceMin)) {
            $collection['experience_min'] = $this->experienceMin;
        }
        if (isset($this->experienceMax)) {
            $collection['experience_max'] = $this->experienceMax;
        }
        if (isset($this->currentJobTitle)) {
            $collection['current_job_title'] = $this->currentJobTitle;
        }
        if (isset($this->workMode)) {
            $collection['work_mode'] = $this->workMode;
        }
        if (isset($this->jobType)) {
            $collection['job_type'] = $this->jobType;
        }
        if (isset($this->noticePeriod)) {
            $collection['notice_period'] = $this->noticePeriod;
        }
        if (isset($this->immediateJoiner)) {
            $collection['immediate_joiner'] = $this->immediateJoiner;
        }

        return $collection;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

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

    public function getCurrentJobTitle(): ?string
    {
        return $this->currentJobTitle;
    }

    public function setCurrentJobTitle(?string $currentJobTitle): self
    {
        $this->currentJobTitle = $currentJobTitle;

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

    public function getNoticePeriod(): ?string
    {
        return $this->noticePeriod;
    }

    public function setNoticePeriod(?string $noticePeriod): self
    {
        $this->noticePeriod = $noticePeriod;

        return $this;
    }

    public function getImmediateJoiner(): ?bool
    {
        return $this->immediateJoiner;
    }

    public function setImmediateJoiner(?bool $immediateJoiner): self
    {
        $this->immediateJoiner = $immediateJoiner;

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
}
