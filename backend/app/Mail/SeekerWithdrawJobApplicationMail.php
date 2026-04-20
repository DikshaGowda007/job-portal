<?php

namespace App\Mail;

use App\Dto\WithdrawJobApplicationMailDto;
use Illuminate\Mail\Mailable;

class SeekerWithdrawJobApplicationMail extends Mailable
{
    public $subjectLine;

    private WithdrawJobApplicationMailDto $withdrawJobApplicationMailDto;

    public function __construct(WithdrawJobApplicationMailDto $withdrawJobApplicationMailDto)
    {
        $this->subjectLine = 'Job Application Withdrawn - Confirmation';
        $this->withdrawJobApplicationMailDto = $withdrawJobApplicationMailDto;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.job_application.seeker_withdraw_job_application')
            ->with([
                'jobTitle' => $this->withdrawJobApplicationMailDto->getJobTitle(),
                'candidateName' => $this->withdrawJobApplicationMailDto->getCandidateName(),
                'candidateEmail' => $this->withdrawJobApplicationMailDto->getCandidateEmail(),
                'applicationId' => $this->withdrawJobApplicationMailDto->getApplicationId(),
            ]);
    }
}
