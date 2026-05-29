<?php

namespace App\Repositories\MySql\V1;

use App\Models\EmailLog;
use App\Repositories\DAO\V1\EmailLogsDAO;
use App\Repositories\V1\EmailLogsRepository;

class EmailLogsRepositoryImpl implements EmailLogsRepository
{
    public function insert(EmailLogsDAO $emailLogsDAO)
    {
        return EmailLog::create($emailLogsDAO->toArray());
    }
}
