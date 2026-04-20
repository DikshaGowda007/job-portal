<?php

namespace App\Repositories\MySql\V1;

use App\Models\JobApplicationHistory;
use App\Repositories\DAO\V1\JobApplicationHistoryDAO;
use App\Repositories\V1\JobApplicationHistoryRepository;
use Carbon\Carbon;

class JobApplicationHistoryRepositoryImpl implements JobApplicationHistoryRepository
{
    public function insert(JobApplicationHistoryDAO $jobApplicationHistoryDAO): JobApplicationHistory
    {
        $jobApplicationHistoryDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobApplicationHistoryDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobApplicationHistory::create($jobApplicationHistoryDAO->toArray());
    }
}
