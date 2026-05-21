<?php

namespace App\Repositories\DAO\V1;

class JobSeekerEducationDAO
{
    private ?int $id = null;

    private ?int $jobSeekerProfileId = null;

    private ?string $degree = null;

    private ?string $fieldOfStudy = null;

    private ?string $institution = null;

    private ?string $location = null;

    private ?int $startYear = null;

    private ?int $endYear = null;

    private ?bool $isCurrent = null;

    private ?string $grade = null;

    private ?float $percentage = null;

    private ?float $cgpa = null;

    private ?string $description = null;

    private ?array $achievements = null;

    private ?int $isDeleted = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->jobSeekerProfileId)) {
            $collection['job_seeker_profile_id'] = $this->jobSeekerProfileId;
        }
        if (isset($this->degree)) {
            $collection['degree'] = $this->degree;
        }
        if (isset($this->fieldOfStudy)) {
            $collection['field_of_study'] = $this->fieldOfStudy;
        }
        if (isset($this->institution)) {
            $collection['institution'] = $this->institution;
        }
        if (isset($this->location)) {
            $collection['location'] = $this->location;
        }
        if (isset($this->startYear)) {
            $collection['start_year'] = $this->startYear;
        }
        if (isset($this->endYear)) {
            $collection['end_year'] = $this->endYear;
        }
        if (isset($this->isCurrent)) {
            $collection['is_current'] = $this->isCurrent;
        }
        if (isset($this->grade)) {
            $collection['grade'] = $this->grade;
        }
        if (isset($this->percentage)) {
            $collection['percentage'] = $this->percentage;
        }
        if (isset($this->cgpa)) {
            $collection['cgpa'] = $this->cgpa;
        }
        if (isset($this->description)) {
            $collection['description'] = $this->description;
        }
        if (isset($this->achievements)) {
            $collection['achievements'] = $this->achievements;
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

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(?string $degree): self
    {
        $this->degree = $degree;

        return $this;
    }

    public function getFieldOfStudy(): ?string
    {
        return $this->fieldOfStudy;
    }

    public function setFieldOfStudy(?string $fieldOfStudy): self
    {
        $this->fieldOfStudy = $fieldOfStudy;

        return $this;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(?string $institution): self
    {
        $this->institution = $institution;

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

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(?int $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

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

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(?float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getCgpa(): ?float
    {
        return $this->cgpa;
    }

    public function setCgpa(?float $cgpa): self
    {
        $this->cgpa = $cgpa;

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

    public function getAchievements(): ?array
    {
        return $this->achievements;
    }

    public function setAchievements(?array $achievements): self
    {
        $this->achievements = $achievements;

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
