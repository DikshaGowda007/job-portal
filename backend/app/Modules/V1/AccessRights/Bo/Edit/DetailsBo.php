<?php

namespace App\Modules\V1\AccessRights\Bo\Edit;

class DetailsBo
{
    private ?int $userId = null;

    private ?array $accessDetails = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }
        if (isset($this->accessDetails)) {
            $collection['access_details'] = $this->accessDetails;
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
     * Get the value of accessDetails
     */
    public function getAccessDetails(): ?array
    {
        return $this->accessDetails;
    }

    /**
     * Set the value of accessDetails
     */
    public function setAccessDetails(?array $accessDetails): self
    {
        $this->accessDetails = $accessDetails;

        return $this;
    }
}
