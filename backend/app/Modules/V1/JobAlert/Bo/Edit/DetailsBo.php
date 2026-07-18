<?php

namespace App\Modules\V1\JobAlert\Bo\Edit;

class DetailsBo
{
    private ?int $id = null;

    private ?string $keyword = null;

    private ?string $location = null;

    private ?int $jobCategoryId = null;

    private ?string $jobType = null;

    private ?string $workMode = null;

    private ?string $experienceLevel = null;

    public function toArray(): array
    {
        $data = [];

        if (isset($this->keyword)) {
            $data['keyword'] = $this->keyword;
        }
        if (isset($this->location)) {
            $data['location'] = $this->location;
        }
        if (isset($this->jobCategoryId)) {
            $data['job_category_id'] = $this->jobCategoryId;
        }
        if (isset($this->jobType)) {
            $data['job_type'] = $this->jobType;
        }
        if (isset($this->workMode)) {
            $data['work_mode'] = $this->workMode;
        }
        if (isset($this->experienceLevel)) {
            $data['experience_level'] = $this->experienceLevel;
        }

        return $data;
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

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

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

    public function getJobType(): ?string
    {
        return $this->jobType;
    }

    public function setJobType(?string $jobType): self
    {
        $this->jobType = $jobType;

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

    public function getExperienceLevel(): ?string
    {
        return $this->experienceLevel;
    }

    public function setExperienceLevel(?string $experienceLevel): self
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }
}
