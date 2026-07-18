<?php

namespace App\Repositories\V1;

use App\Models\JobAlert;
use App\Repositories\DAO\V1\JobAlertDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobAlertRepository
{
    public function insert(JobAlertDAO $jobAlertDAO): JobAlert;

    public function fetchByUserId(int $userId): Collection;

    public function findByIdAndUserId(int $id, int $userId): ?JobAlert;

    public function updateById(int $id, JobAlertDAO $jobAlertDAO): bool|int;

    public function findAllActive(): Collection;
}
