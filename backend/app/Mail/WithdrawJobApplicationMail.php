<?php

namespace App\Mail;

use App\Dto\WithdrawJobApplicationMailDto;
use Illuminate\Mail\Mailable;

class WithdrawJobApplicationMail extends Mailable
{
    public $subjectLine;

    private WithdrawJobApplicationMailDto $withdrawJobApplicationMailDto;

    public function __construct(WithdrawJobApplicationMailDto $withdrawJobApplicationMailDto)
    {
        $this->subjectLine = 'Job Application Withdrawn';
        $this->withdrawJobApplicationMailDto = $withdrawJobApplicationMailDto;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.withdraw_job_application')
            ->with([
                'recruiterName' => $this->withdrawJobApplicationMailDto->getRecruiterName(),
                'jobTitle' => $this->withdrawJobApplicationMailDto->getJobTitle(),
                'candidateName' => $this->withdrawJobApplicationMailDto->getCandidateName(),
                'candidateEmail' => $this->withdrawJobApplicationMailDto->getCandidateEmail(),
                'applicationId' => $this->withdrawJobApplicationMailDto->getApplicationId(),
            ]);
    }
}
