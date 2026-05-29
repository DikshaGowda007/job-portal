<?php

namespace App\Repositories\V1;

use App\Repositories\DAO\V1\MessageLogDAO;

interface MessageLogRepository
{
    public function insert(MessageLogDAO $messageLogDAO);
}
