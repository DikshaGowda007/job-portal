<?php

namespace App\Modules\V1\RecruiterProfile\Bo\Update;

class DetailsBo
{
    private ?int $userId = null;

    private ?string $companyName = null;

    private ?string $companyAbout = null;

    private ?string $companyWebsite = null;

    private ?string $companySize = null;

    private ?string $industry = null;

    private ?string $companyPhone = null;

    private ?string $city = null;

    private ?string $state = null;

    private ?string $country = null;

    private ?string $linkedinUrl = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->companyName)) {
            $collection['company_name'] = $this->companyName;
        }
        if (isset($this->companyAbout)) {
            $collection['company_about'] = $this->companyAbout;
        }
        if (isset($this->companyWebsite)) {
            $collection['company_website'] = $this->companyWebsite;
        }
        if (isset($this->companySize)) {
            $collection['company_size'] = $this->companySize;
        }
        if (isset($this->industry)) {
            $collection['industry'] = $this->industry;
        }
        if (isset($this->companyPhone)) {
            $collection['company_phone'] = $this->companyPhone;
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
        if (isset($this->linkedinUrl)) {
            $collection['linkedin_url'] = $this->linkedinUrl;
        }

        return $collection;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of companyName
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     */
    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get the value of companyAbout
     */
    public function getCompanyAbout(): ?string
    {
        return $this->companyAbout;
    }

    /**
     * Set the value of companyAbout
     */
    public function setCompanyAbout(?string $companyAbout): self
    {
        $this->companyAbout = $companyAbout;

        return $this;
    }

    /**
     * Get the value of companyWebsite
     */
    public function getCompanyWebsite(): ?string
    {
        return $this->companyWebsite;
    }

    /**
     * Set the value of companyWebsite
     */
    public function setCompanyWebsite(?string $companyWebsite): self
    {
        $this->companyWebsite = $companyWebsite;

        return $this;
    }

    /**
     * Get the value of companySize
     */
    public function getCompanySize(): ?string
    {
        return $this->companySize;
    }

    /**
     * Set the value of companySize
     */
    public function setCompanySize(?string $companySize): self
    {
        $this->companySize = $companySize;

        return $this;
    }

    /**
     * Get the value of industry
     */
    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    /**
     * Set the value of industry
     */
    public function setIndustry(?string $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * Get the value of companyPhone
     */
    public function getCompanyPhone(): ?string
    {
        return $this->companyPhone;
    }

    /**
     * Set the value of companyPhone
     */
    public function setCompanyPhone(?string $companyPhone): self
    {
        $this->companyPhone = $companyPhone;

        return $this;
    }

    /**
     * Get the value of city
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of state
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Set the value of state
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of country
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set the value of country
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of linkedinUrl
     */
    public function getLinkedinUrl(): ?string
    {
        return $this->linkedinUrl;
    }

    /**
     * Set the value of linkedinUrl
     */
    public function setLinkedinUrl(?string $linkedinUrl): self
    {
        $this->linkedinUrl = $linkedinUrl;

        return $this;
    }
}
