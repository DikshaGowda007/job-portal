<?php

namespace App\Modules\V1\JobApplication\Bo\UpdateStatus;

class DetailsBo
{
    private ?int $applicationId = null;

    private ?string $status = null;

    private ?string $recruiterNotes = null;

    private ?int $reviewedByUserId = null;

    private ?string $interviewScheduledAt = null;

    private ?string $interviewLocation = null;

    public function toArray()
    {
        $data = [];

        if (isset($this->applicationId)) {
            $data['application_id'] = $this->applicationId;
        }
        if (isset($this->status)) {
            $data['status'] = $this->status;
        }
        if (isset($this->recruiterNotes)) {
            $data['recruiter_notes'] = $this->recruiterNotes;
        }
        if (isset($this->reviewedByUserId)) {
            $data['reviewed_by_user_id'] = $this->reviewedByUserId;
        }
        if (isset($this->interviewScheduledAt)) {
            $data['interview_scheduled_at'] = $this->interviewScheduledAt;
        }
        if (isset($this->interviewLocation)) {
            $data['interview_location'] = $this->interviewLocation;
        }

        return $data;
    }

    /**
     * Get the value of applicationId
     */
    public function getApplicationId(): ?int
    {
        return $this->applicationId;
    }

    /**
     * Set the value of applicationId
     */
    public function setApplicationId(?int $applicationId): self
    {
        $this->applicationId = $applicationId;

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
     * Get the value of recruiterNotes
     */
    public function getRecruiterNotes(): ?string
    {
        return $this->recruiterNotes;
    }

    /**
     * Set the value of recruiterNotes
     */
    public function setRecruiterNotes(?string $recruiterNotes): self
    {
        $this->recruiterNotes = $recruiterNotes;

        return $this;
    }

    /**
     * Get the value of reviewedByUserId
     */
    public function getReviewedByUserId(): ?int
    {
        return $this->reviewedByUserId;
    }

    /**
     * Set the value of reviewedByUserId
     */
    public function setReviewedByUserId(?int $reviewedByUserId): self
    {
        $this->reviewedByUserId = $reviewedByUserId;

        return $this;
    }

    /**
     * Get the value of interviewScheduledAt
     */
    public function getInterviewScheduledAt(): ?string
    {
        return $this->interviewScheduledAt;
    }

    /**
     * Set the value of interviewScheduledAt
     */
    public function setInterviewScheduledAt(?string $interviewScheduledAt): self
    {
        $this->interviewScheduledAt = $interviewScheduledAt;

        return $this;
    }

    /**
     * Get the value of interviewLocation
     */
    public function getInterviewLocation(): ?string
    {
        return $this->interviewLocation;
    }

    /**
     * Set the value of interviewLocation
     */
    public function setInterviewLocation(?string $interviewLocation): self
    {
        $this->interviewLocation = $interviewLocation;

        return $this;
    }
}
