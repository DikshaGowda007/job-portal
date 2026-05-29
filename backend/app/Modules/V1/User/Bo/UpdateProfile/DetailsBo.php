<?php

namespace App\Modules\V1\User\Bo\UpdateProfile;

class DetailsBo
{
    private int $userId;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $email = null;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function toArray(): array
    {
        $collection = [
            'user_id' => $this->userId,
        ];

        if (isset($this->firstName)) {
            $collection['first_name'] = $this->firstName;
        }
        if (isset($this->lastName)) {
            $collection['last_name'] = $this->lastName;
        }
        if (isset($this->email)) {
            $collection['email'] = $this->email;
        }

        return $collection;
    }
}
