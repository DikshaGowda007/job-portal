<?php

namespace App\Repositories\MySql\V1;

use App\Models\MessageLog;
use App\Repositories\DAO\V1\MessageLogDAO;
use App\Repositories\V1\MessageLogRepository;

class MessageLogRepositoryImpl implements MessageLogRepository
{
    public function insert(MessageLogDAO $messageLogDAO)
    {
        return MessageLog::create($messageLogDAO->toArray());
    }
}
