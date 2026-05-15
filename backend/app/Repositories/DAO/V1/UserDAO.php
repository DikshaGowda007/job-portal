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

    private ?string $createdAt = null;

    private ?string $updatedAt = null;

    public function toArray()
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of verified
     */
    public function getVerified()
    {
        return $this->verified;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Set the value of verified
     *
     * @return self
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of lastLogin
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set the value of lastLogin
     *
     * @return self
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }
}
