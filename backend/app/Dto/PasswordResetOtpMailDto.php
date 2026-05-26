<?php

namespace App\Dto;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PasswordResetOtpMailDto
{
    private ?string $userName = null;

    private ?string $otp = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userName)) {
            $collection['user_name'] = $this->userName;
        }

        if (isset($this->otp)) {
            $collection['otp'] = $this->otp;
        }

        return $collection;
    }

    public function validate(): void
    {
        $data = $this->toArray();

        $rules = [
            'user_name' => ['required', 'string', 'max:255'],
            'otp' => ['required', 'string', 'size:6'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Get the value of userName
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * Set the value of userName
     */
    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get the value of otp
     */
    public function getOtp(): ?string
    {
        return $this->otp;
    }

    /**
     * Set the value of otp
     */
    public function setOtp(?string $otp): self
    {
        $this->otp = $otp;

        return $this;
    }
}
