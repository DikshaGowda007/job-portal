<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $candidateName;

    public string $jobTitle;

    public string $companyName;

    public string $oldStatus;

    public string $newStatus;

    public ?string $recruiterNotes;

    public ?string $interviewScheduledAt;

    public ?string $interviewLocation;

    public function __construct(
        string $candidateName,
        string $jobTitle,
        string $companyName,
        string $oldStatus,
        string $newStatus,
        ?string $recruiterNotes = null,
        ?string $interviewScheduledAt = null,
        ?string $interviewLocation = null
    ) {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->companyName = $companyName;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->recruiterNotes = $recruiterNotes;
        $this->interviewScheduledAt = $interviewScheduledAt;
        $this->interviewLocation = $interviewLocation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Status Update - '.$this->jobTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application_status_changed',
            with: [
                'candidateName' => $this->candidateName,
                'jobTitle' => $this->jobTitle,
                'companyName' => $this->companyName,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'recruiterNotes' => $this->recruiterNotes,
                'interviewScheduledAt' => $this->interviewScheduledAt,
                'interviewLocation' => $this->interviewLocation,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
