<?php

namespace App\Repositories\V1;

use App\Models\JobApplicationHistory;
use App\Repositories\DAO\V1\JobApplicationHistoryDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobApplicationHistoryRepository
{
    public function insert(JobApplicationHistoryDAO $jobApplicationHistoryDAO): JobApplicationHistory;

    public function fetchByApplicationId(int $applicationId): Collection;
}
