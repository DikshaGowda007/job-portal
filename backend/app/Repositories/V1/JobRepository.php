<?php

namespace App\Repositories\V1;

use App\Models\JobPost;
use App\Repositories\DAO\V1\JobPostDAO;

interface JobRepository
{
    public function insert(JobPostDAO $jobPostDAO): JobPost;
}
