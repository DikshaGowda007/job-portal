<?php

namespace App\Repositories\DAO\V1;

class UserDAO
{
    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $email = null;

    private ?string $password = null;

    private ?int $userType = null;

    private ?int $verified = null;

    private ?string $lastLogin = null;

    private ?string $status = null;

    private ?string $phone = null;

    private ?string $emailVerifiedAt = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->firstName)) {
            $collection['first_name'] = $this->firstName;
        }
        if (isset($this->lastName)) {
            $collection['last_name'] = $this->lastName;
        }
        if (isset($this->email)) {
            $collection['email'] = $this->email;
        }
        if (isset($this->password)) {
            $collection['password'] = $this->password;
        }
        if (isset($this->userType)) {
            $collection['user_type'] = $this->userType;
        }
        if (isset($this->verified)) {
            $collection['verified'] = $this->verified;
        }
        if (isset($this->lastLogin)) {
            $collection['last_login'] = $this->lastLogin;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->phone)) {
            $collection['phone'] = $this->phone;
        }
        if (isset($this->emailVerifiedAt)) {
            $collection['email_verified_at'] = $this->emailVerifiedAt;
        }
        if (isset($this->createdAt)) {
            $collection['created_at'] = $this->createdAt;
        }
        if (isset($this->updatedAt)) {
            $collection['updated_at'] = $this->updatedAt;
        }

        return $collection;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of userType
     */
    public function getUserType(): ?int
    {
        return $this->userType;
    }

    /**
     * Set the value of userType
     */
    public function setUserType(?int $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get the value of verified
     */
    public function getVerified(): ?int
    {
        return $this->verified;
    }

    /**
     * Set the value of verified
     */
    public function setVerified(?int $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get the value of lastLogin
     */
    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }

    /**
     * Set the value of lastLogin
     */
    public function setLastLogin(?string $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

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
     * Get the value of phone
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of emailVerifiedAt
     */
    public function getEmailVerifiedAt(): ?string
    {
        return $this->emailVerifiedAt;
    }

    /**
     * Set the value of emailVerifiedAt
     */
    public function setEmailVerifiedAt(?string $emailVerifiedAt): self
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
