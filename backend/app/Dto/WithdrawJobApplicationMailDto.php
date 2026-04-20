<?php

namespace App\Dto;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WithdrawJobApplicationMailDto
{
    private ?string $recruiterName = null;

    private ?string $jobTitle = null;

    private ?string $candidateName = null;

    private ?string $candidateEmail = null;

    private ?int $applicationId = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->recruiterName)) {
            $collection['recruiter_name'] = $this->recruiterName;
        }

        if (isset($this->jobTitle)) {
            $collection['job_title'] = $this->jobTitle;
        }

        if (isset($this->candidateName)) {
            $collection['candidate_name'] = $this->candidateName;
        }

        if (isset($this->candidateEmail)) {
            $collection['candidate_email'] = $this->candidateEmail;
        }

        if (isset($this->applicationId)) {
            $collection['application_id'] = $this->applicationId;
        }

        return $collection;
    }

    public function validate(): void
    {
        $data = $this->toArray();

        $rules = [
            'job_title' => ['required', 'string', 'max:255'],
            'candidate_name' => ['required', 'string', 'max:255'],
            'candidate_email' => ['required', 'email', 'max:255'],
            'application_id' => ['required', 'integer', 'min:1'],
        ];

        // Only validate recruiter_name if present (recruiter email scenario)
        if (isset($data['recruiter_name'])) {
            $rules['recruiter_name'] = ['required', 'string', 'max:255'];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Get the value of recruiterName
     */
    public function getRecruiterName(): ?string
    {
        return $this->recruiterName;
    }

    /**
     * Set the value of recruiterName
     */
    public function setRecruiterName(?string $recruiterName): self
    {
        $this->recruiterName = $recruiterName;

        return $this;
    }

    /**
     * Get the value of jobTitle
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * Set the value of jobTitle
     */
    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get the value of candidateName
     */
    public function getCandidateName(): ?string
    {
        return $this->candidateName;
    }

    /**
     * Set the value of candidateName
     */
    public function setCandidateName(?string $candidateName): self
    {
        $this->candidateName = $candidateName;

        return $this;
    }

    /**
     * Get the value of candidateEmail
     */
    public function getCandidateEmail(): ?string
    {
        return $this->candidateEmail;
    }

    /**
     * Set the value of candidateEmail
     */
    public function setCandidateEmail(?string $candidateEmail): self
    {
        $this->candidateEmail = $candidateEmail;

        return $this;
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
}
