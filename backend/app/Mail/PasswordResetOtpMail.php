<?php

namespace App\Mail;

use App\Dto\PasswordResetOtpMailDto;
use Illuminate\Mail\Mailable;

class PasswordResetOtpMail extends Mailable
{
    private PasswordResetOtpMailDto $passwordResetOtpMailDto;

    public function __construct(PasswordResetOtpMailDto $passwordResetOtpMailDto)
    {
        $this->passwordResetOtpMailDto = $passwordResetOtpMailDto;
    }

    public function build()
    {
        return $this->subject('Password Reset OTP')
            ->view('emails.password_reset_otp')
            ->with([
                'userName' => $this->passwordResetOtpMailDto->getUserName(),
                'otp' => $this->passwordResetOtpMailDto->getOtp(),
            ]);
    }
}
