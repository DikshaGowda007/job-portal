<?php

namespace App\Mail;

use App\Dto\WithdrawJobApplicationMailDto;
use Illuminate\Mail\Mailable;

class RecruiterWithdrawJobApplicationMail extends Mailable
{
    public $subjectLine;

    private WithdrawJobApplicationMailDto $withdrawJobApplicationMailDto;

    public function __construct(WithdrawJobApplicationMailDto $withdrawJobApplicationMailDto)
    {
        $this->subjectLine = 'Candidate Withdrawn Job Application';
        $this->withdrawJobApplicationMailDto = $withdrawJobApplicationMailDto;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.job_application.recruiter_withdraw_job_application')
            ->with([
                'recruiterName' => $this->withdrawJobApplicationMailDto->getRecruiterName(),
                'jobTitle' => $this->withdrawJobApplicationMailDto->getJobTitle(),
                'candidateName' => $this->withdrawJobApplicationMailDto->getCandidateName(),
                'candidateEmail' => $this->withdrawJobApplicationMailDto->getCandidateEmail(),
                'applicationId' => $this->withdrawJobApplicationMailDto->getApplicationId(),
            ]);
    }
}
