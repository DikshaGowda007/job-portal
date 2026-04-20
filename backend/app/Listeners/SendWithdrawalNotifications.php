<?php

namespace App\Listeners;

use App\Events\ApplicationWithdrawn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWithdrawalNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(ApplicationWithdrawn $event): void
    {
        try {
            $mailableClass = $event->sendEmailDto->getMailableClass();
            $toEmail = $event->sendEmailDto->getToEmail();
            $templateDto = $event->templateDto;

            Mail::mailer(env('MAIL_MAILER'))
                ->to($toEmail)
                ->send(new $mailableClass($templateDto));

        } catch (\Throwable $e) {
            throw $e;
        }
    }
}