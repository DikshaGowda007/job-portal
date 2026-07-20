<?php

namespace App\Repositories\DAO\V1;

class JobApplicationHistoryDAO
{
    private ?int $id = null;

    private ?int $jobApplicationId = null;

    private ?string $previousStatus = null;

    private ?string $newStatus = null;

    private ?int $changedBy = null;

    private ?string $notes = null;

    private ?string $interviewScheduledAt = null;

    private ?string $interviewLocation = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->jobApplicationId)) {
            $collection['job_application_id'] = $this->jobApplicationId;
        }
        if (isset($this->previousStatus)) {
            $collection['previous_status'] = $this->previousStatus;
        }
        if (isset($this->newStatus)) {
            $collection['new_status'] = $this->newStatus;
        }
        if (isset($this->changedBy)) {
            $collection['changed_by'] = $this->changedBy;
        }
        if (isset($this->notes)) {
            $collection['notes'] = $this->notes;
        }
        if (isset($this->interviewScheduledAt)) {
            $collection['interview_scheduled_at'] = $this->interviewScheduledAt;
        }
        if (isset($this->interviewLocation)) {
            $collection['interview_location'] = $this->interviewLocation;
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

    public function getJobApplicationId(): ?int
    {
        return $this->jobApplicationId;
    }

    public function setJobApplicationId(?int $jobApplicationId): self
    {
        $this->jobApplicationId = $jobApplicationId;

        return $this;
    }

    public function getPreviousStatus(): ?string
    {
        return $this->previousStatus;
    }

    public function setPreviousStatus(?string $previousStatus): self
    {
        $this->previousStatus = $previousStatus;

        return $this;
    }

    public function getNewStatus(): ?string
    {
        return $this->newStatus;
    }

    public function setNewStatus(?string $newStatus): self
    {
        $this->newStatus = $newStatus;

        return $this;
    }

    public function getChangedBy(): ?int
    {
        return $this->changedBy;
    }

    public function setChangedBy(?int $changedBy): self
    {
        $this->changedBy = $changedBy;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getInterviewScheduledAt(): ?string
    {
        return $this->interviewScheduledAt;
    }

    public function setInterviewScheduledAt(?string $interviewScheduledAt): self
    {
        $this->interviewScheduledAt = $interviewScheduledAt;

        return $this;
    }

    public function getInterviewLocation(): ?string
    {
        return $this->interviewLocation;
    }

    public function setInterviewLocation(?string $interviewLocation): self
    {
        $this->interviewLocation = $interviewLocation;

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
