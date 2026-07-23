<?php

namespace App\Repositories\DAO\V1;

class ShortlistedCandidateDAO
{
    private ?int $recruiterUserId = null;

    private ?int $candidateUserId = null;

    private ?int $isDeleted = null;

    private ?string $createdAt = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->recruiterUserId)) {
            $collection['recruiter_user_id'] = $this->recruiterUserId;
        }
        if (isset($this->candidateUserId)) {
            $collection['candidate_user_id'] = $this->candidateUserId;
        }
        if (isset($this->isDeleted)) {
            $collection['is_deleted'] = $this->isDeleted;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }

        return $collection;
    }

    public function getRecruiterUserId(): ?int
    {
        return $this->recruiterUserId;
    }

    public function setRecruiterUserId(?int $recruiterUserId): self
    {
        $this->recruiterUserId = $recruiterUserId;

        return $this;
    }

    public function getCandidateUserId(): ?int
    {
        return $this->candidateUserId;
    }

    public function setCandidateUserId(?int $candidateUserId): self
    {
        $this->candidateUserId = $candidateUserId;

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
}
