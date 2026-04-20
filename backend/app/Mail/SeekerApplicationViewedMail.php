<?php

namespace App\Mail;

use App\Dto\ApplicationViewedMailDto;
use Illuminate\Mail\Mailable;

class SeekerApplicationViewedMail extends Mailable
{
    public $subjectLine;

    private ApplicationViewedMailDto $applicationViewedMailDto;

    public function __construct(ApplicationViewedMailDto $applicationViewedMailDto)
    {
        $this->subjectLine = 'Your Job Application Has Been Viewed';
        $this->applicationViewedMailDto = $applicationViewedMailDto;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.job_application.seeker_application_viewed')
            ->with([
                'recruiterName' => $this->applicationViewedMailDto->getRecruiterName(),
                'jobTitle' => $this->applicationViewedMailDto->getJobTitle(),
                'candidateName' => $this->applicationViewedMailDto->getCandidateName(),
                'candidateEmail' => $this->applicationViewedMailDto->getCandidateEmail(),
                'applicationId' => $this->applicationViewedMailDto->getApplicationId(),
                'companyName' => $this->applicationViewedMailDto->getCompanyName(),
            ]);
    }
}
