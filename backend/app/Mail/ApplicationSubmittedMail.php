<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recruiterName;

    public string $jobTitle;

    public string $candidateName;

    public string $candidateEmail;

    public int $applicationId;

    public function __construct(
        string $recruiterName,
        string $jobTitle,
        string $candidateName,
        string $candidateEmail,
        int $applicationId
    ) {
        $this->recruiterName = $recruiterName;
        $this->jobTitle = $jobTitle;
        $this->candidateName = $candidateName;
        $this->candidateEmail = $candidateEmail;
        $this->applicationId = $applicationId;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application Received - '.$this->jobTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application_submitted',
            with: [
                'recruiterName' => $this->recruiterName,
                'jobTitle' => $this->jobTitle,
                'candidateName' => $this->candidateName,
                'candidateEmail' => $this->candidateEmail,
                'applicationId' => $this->applicationId,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
