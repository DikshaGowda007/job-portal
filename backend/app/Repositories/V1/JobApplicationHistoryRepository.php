<?php

namespace App\Repositories\V1;

use App\Models\JobApplicationHistory;
use App\Repositories\DAO\V1\JobApplicationHistoryDAO;

interface JobApplicationHistoryRepository
{
    public function insert(JobApplicationHistoryDAO $jobApplicationHistoryDAO): JobApplicationHistory;
}
