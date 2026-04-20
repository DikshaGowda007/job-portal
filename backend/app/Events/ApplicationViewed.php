<?php

namespace App\Events;

use App\Modules\V1\Email\Dto\SendEmailDto;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationViewed
{
    use Dispatchable, SerializesModels;

    /**
     * @param  SendEmailDto  $sendEmailDto  - Contains metadata (to, class, etc.)
     * @param  object  $templateDto  - Contains the actual content (names, titles, etc.)
     */
    public function __construct(
        public SendEmailDto $sendEmailDto,
        public object $templateDto
    ) {}
}
