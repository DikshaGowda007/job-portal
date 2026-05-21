<?php

namespace App\Modules\V1\JobSeekerProfile\Bo\Education\Update;

class DetailsBo
{
    private ?int $educationId = null;

    private ?string $degree = null;

    private ?string $fieldOfStudy = null;

    private ?string $institution = null;

    private ?string $location = null;

    private ?int $startYear = null;

    private ?int $endYear = null;

    private ?bool $isCurrent = null;

    private ?string $description = null;

    public function getEducationId(): ?int
    {
        return $this->educationId;
    }

    public function setEducationId(?int $educationId): self
    {
        $this->educationId = $educationId;

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
