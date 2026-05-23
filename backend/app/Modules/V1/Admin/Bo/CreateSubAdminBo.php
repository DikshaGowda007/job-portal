<?php

namespace App\Modules\V1\Admin\Bo;

class CreateSubAdminBo
{
    private string $email;

    private string $firstName;

    private string $lastName;

    private ?string $phone;

    private string $password;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->email)) {
            $collection['email'] = $this->email;
        }
        if (isset($this->firstName)) {
            $collection['first_name'] = $this->firstName;
        }
        if (isset($this->lastName)) {
            $collection['last_name'] = $this->lastName;
        }
        if (isset($this->phone)) {
            $collection['phone'] = $this->phone;
        }
        if (isset($this->password)) {
            $collection['password'] = $this->password;
        }

        return $collection;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
