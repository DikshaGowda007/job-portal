<?php

namespace App\Modules\V1\Admin\Bo;

class ViewUserBo
{
    private int $userId;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }

        return $collection;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
