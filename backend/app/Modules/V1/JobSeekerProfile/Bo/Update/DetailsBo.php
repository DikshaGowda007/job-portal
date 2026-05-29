<?php

namespace App\Modules\V1\JobSeekerProfile\Bo\Update;

class DetailsBo
{
    private ?int $userId = null;

    private ?string $headline = null;

    private ?string $summary = null;

    private ?string $phone = null;

    private ?string $dateOfBirth = null;

    private ?string $gender = null;

    private ?string $city = null;

    private ?string $state = null;

    private ?string $country = null;

    private ?string $pincode = null;

    private ?string $currentJobTitle = null;

    private ?string $currentCompany = null;

    private ?int $totalExperienceYears = null;

    private ?int $totalExperienceMonths = null;

    private ?float $expectedSalary = null;

    private ?string $expectedSalaryCurrency = null;

    private ?float $currentSalary = null;

    private ?string $currentSalaryCurrency = null;

    private ?array $preferredJobTypes = null;

    private ?array $preferredWorkModes = null;

    private ?array $preferredLocations = null;

    private ?int $noticePeriod = null;

    private ?bool $immediateJoiner = null;

    private ?array $skills = null;

    private ?string $linkedinUrl = null;

    private ?string $githubUrl = null;

    private ?string $portfolioUrl = null;

    private ?bool $isPublic = null;

    private ?bool $openToOpportunities = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPincode(): ?string
    {
        return $this->pincode;
    }

    public function setPincode(?string $pincode): self
    {
        $this->pincode = $pincode;

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

    public function getCurrentCompany(): ?string
    {
        return $this->currentCompany;
    }

    public function setCurrentCompany(?string $currentCompany): self
    {
        $this->currentCompany = $currentCompany;

        return $this;
    }

    public function getTotalExperienceYears(): ?int
    {
        return $this->totalExperienceYears;
    }

    public function setTotalExperienceYears(?int $totalExperienceYears): self
    {
        $this->totalExperienceYears = $totalExperienceYears;

        return $this;
    }

    public function getTotalExperienceMonths(): ?int
    {
        return $this->totalExperienceMonths;
    }

    public function setTotalExperienceMonths(?int $totalExperienceMonths): self
    {
        $this->totalExperienceMonths = $totalExperienceMonths;

        return $this;
    }

    public function getExpectedSalary(): ?float
    {
        return $this->expectedSalary;
    }

    public function setExpectedSalary(?float $expectedSalary): self
    {
        $this->expectedSalary = $expectedSalary;

        return $this;
    }

    public function getExpectedSalaryCurrency(): ?string
    {
        return $this->expectedSalaryCurrency;
    }

    public function setExpectedSalaryCurrency(?string $expectedSalaryCurrency): self
    {
        $this->expectedSalaryCurrency = $expectedSalaryCurrency;

        return $this;
    }

    public function getCurrentSalary(): ?float
    {
        return $this->currentSalary;
    }

    public function setCurrentSalary(?float $currentSalary): self
    {
        $this->currentSalary = $currentSalary;

        return $this;
    }

    public function getCurrentSalaryCurrency(): ?string
    {
        return $this->currentSalaryCurrency;
    }

    public function setCurrentSalaryCurrency(?string $currentSalaryCurrency): self
    {
        $this->currentSalaryCurrency = $currentSalaryCurrency;

        return $this;
    }

    public function getPreferredJobTypes(): ?array
    {
        return $this->preferredJobTypes;
    }

    public function setPreferredJobTypes(?array $preferredJobTypes): self
    {
        $this->preferredJobTypes = $preferredJobTypes;

        return $this;
    }

    public function getPreferredWorkModes(): ?array
    {
        return $this->preferredWorkModes;
    }

    public function setPreferredWorkModes(?array $preferredWorkModes): self
    {
        $this->preferredWorkModes = $preferredWorkModes;

        return $this;
    }

    public function getPreferredLocations(): ?array
    {
        return $this->preferredLocations;
    }

    public function setPreferredLocations(?array $preferredLocations): self
    {
        $this->preferredLocations = $preferredLocations;

        return $this;
    }

    public function getNoticePeriod(): ?int
    {
        return $this->noticePeriod;
    }

    public function setNoticePeriod(?int $noticePeriod): self
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

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(?array $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function getLinkedinUrl(): ?string
    {
        return $this->linkedinUrl;
    }

    public function setLinkedinUrl(?string $linkedinUrl): self
    {
        $this->linkedinUrl = $linkedinUrl;

        return $this;
    }

    public function getGithubUrl(): ?string
    {
        return $this->githubUrl;
    }

    public function setGithubUrl(?string $githubUrl): self
    {
        $this->githubUrl = $githubUrl;

        return $this;
    }

    public function getPortfolioUrl(): ?string
    {
        return $this->portfolioUrl;
    }

    public function setPortfolioUrl(?string $portfolioUrl): self
    {
        $this->portfolioUrl = $portfolioUrl;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getOpenToOpportunities(): ?bool
    {
        return $this->openToOpportunities;
    }

    public function setOpenToOpportunities(?bool $openToOpportunities): self
    {
        $this->openToOpportunities = $openToOpportunities;

        return $this;
    }

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->headline)) {
            $collection['headline'] = $this->headline;
        }
        if (isset($this->summary)) {
            $collection['summary'] = $this->summary;
        }
        if (isset($this->phone)) {
            $collection['phone'] = $this->phone;
        }
        if (isset($this->dateOfBirth)) {
            $collection['date_of_birth'] = $this->dateOfBirth;
        }
        if (isset($this->gender)) {
            $collection['gender'] = $this->gender;
        }
        if (isset($this->city)) {
            $collection['city'] = $this->city;
        }
        if (isset($this->state)) {
            $collection['state'] = $this->state;
        }
        if (isset($this->country)) {
            $collection['country'] = $this->country;
        }
        if (isset($this->pincode)) {
            $collection['pincode'] = $this->pincode;
        }
        if (isset($this->currentJobTitle)) {
            $collection['current_job_title'] = $this->currentJobTitle;
        }
        if (isset($this->currentCompany)) {
            $collection['current_company'] = $this->currentCompany;
        }
        if (isset($this->totalExperienceYears)) {
            $collection['total_experience_years'] = $this->totalExperienceYears;
        }
        if (isset($this->totalExperienceMonths)) {
            $collection['total_experience_months'] = $this->totalExperienceMonths;
        }
        if (isset($this->expectedSalary)) {
            $collection['expected_salary'] = $this->expectedSalary;
        }
        if (isset($this->expectedSalaryCurrency)) {
            $collection['expected_salary_currency'] = $this->expectedSalaryCurrency;
        }
        if (isset($this->currentSalary)) {
            $collection['current_salary'] = $this->currentSalary;
        }
        if (isset($this->currentSalaryCurrency)) {
            $collection['current_salary_currency'] = $this->currentSalaryCurrency;
        }
        if (isset($this->preferredJobTypes)) {
            $collection['preferred_job_types'] = $this->preferredJobTypes;
        }
        if (isset($this->preferredWorkModes)) {
            $collection['preferred_work_modes'] = $this->preferredWorkModes;
        }
        if (isset($this->preferredLocations)) {
            $collection['preferred_locations'] = $this->preferredLocations;
        }
        if (isset($this->noticePeriod)) {
            $collection['notice_period'] = $this->noticePeriod;
        }
        if (isset($this->immediateJoiner)) {
            $collection['immediate_joiner'] = $this->immediateJoiner;
        }
        if (isset($this->skills)) {
            $collection['skills'] = $this->skills;
        }
        if (isset($this->linkedinUrl)) {
            $collection['linkedin_url'] = $this->linkedinUrl;
        }
        if (isset($this->githubUrl)) {
            $collection['github_url'] = $this->githubUrl;
        }
        if (isset($this->portfolioUrl)) {
            $collection['portfolio_url'] = $this->portfolioUrl;
        }
        if (isset($this->isPublic)) {
            $collection['is_public'] = $this->isPublic;
        }
        if (isset($this->openToOpportunities)) {
            $collection['open_to_opportunities'] = $this->openToOpportunities;
        }

        return $collection;
    }
}
