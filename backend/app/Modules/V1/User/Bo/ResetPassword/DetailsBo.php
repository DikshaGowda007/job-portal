<?php

namespace App\Modules\V1\User\Bo\ResetPassword;

class DetailsBo
{
    private string $email;

    private string $otp;

    private string $newPassword;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->email)) {
            $collection['email'] = $this->email;
        }

        if (isset($this->otp)) {
            $collection['otp'] = $this->otp;
        }

        if (isset($this->newPassword)) {
            $collection['new_password'] = $this->newPassword;
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
     * Get the value of otp
     */
    public function getOtp(): string
    {
        return $this->otp;
    }

    /**
     * Set the value of otp
     */
    public function setOtp(string $otp): self
    {
        $this->otp = $otp;

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
