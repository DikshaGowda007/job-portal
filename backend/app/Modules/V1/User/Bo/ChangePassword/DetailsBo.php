<?php

namespace App\Modules\V1\User\Bo\ChangePassword;

class DetailsBo
{
    private string $currentPassword;

    private string $newPassword;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->currentPassword)) {
            $collection['current_password'] = $this->currentPassword;
        }
        if (isset($this->newPassword)) {
            $collection['new_password'] = $this->newPassword;
        }

        return $collection;
    }

    /**
     * Get the value of currentPassword
     */
    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    /**
     * Set the value of currentPassword
     */
    public function setCurrentPassword(string $currentPassword): self
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    /**
     * Get the value of newPassword
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * Set the value of newPassword
     */
    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
