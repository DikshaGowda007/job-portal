<?php

namespace App\Repositories\V1;

use App\Models\ApplicationMessage;
use App\Repositories\DAO\V1\ApplicationMessageDAO;
use Illuminate\Database\Eloquent\Collection;

interface ApplicationMessageRepository
{
    public function insert(ApplicationMessageDAO $applicationMessageDAO): ApplicationMessage;

    public function fetchByUserIdWithJobPostsAndSenderId(int $userId): Collection;

    public function fetchByRecruiterIdWithJobPostsAndSenderId(int $recruiterId): Collection;

    public function fetchByApplicationIdAndSenderId(int $applicationId, int $senderUserId): Collection;

    public function updateReadAtByIds(array $ids, ApplicationMessageDAO $applicationMessageDAO);
}
