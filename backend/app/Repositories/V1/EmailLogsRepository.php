<?php

namespace App\Repositories\V1;

use App\Repositories\DAO\V1\EmailLogsDAO;

interface EmailLogsRepository
{
    public function insert(EmailLogsDAO $emailLogsDAO);
}
