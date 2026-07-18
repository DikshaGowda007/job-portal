<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobAlertMatchMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $seekerName;

    public string $jobTitle;

    public string $companyName;

    public int $jobId;

    public function __construct(
        string $seekerName,
        string $jobTitle,
        string $companyName,
        int $jobId
    ) {
        $this->seekerName = $seekerName;
        $this->jobTitle = $jobTitle;
        $this->companyName = $companyName;
        $this->jobId = $jobId;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Matches Your Alert - '.$this->jobTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job_alert_match',
            with: [
                'seekerName' => $this->seekerName,
                'jobTitle' => $this->jobTitle,
                'companyName' => $this->companyName,
                'jobId' => $this->jobId,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
