<?php

namespace App\Repositories\V1;

use App\Models\SavedJob;
use App\Repositories\DAO\V1\SavedJobDAO;
use Illuminate\Database\Eloquent\Collection;

interface SavedJobRepository
{
    public function insert(SavedJobDAO $savedJobDao): SavedJob;

    public function fetchByUserId(int $userId): Collection;

    public function updateById(int $id, SavedJobDAO $savedJobDao): bool|int;

    public function findByUserAndJob(int $userId, int $jobPostId): ?SavedJob;

    public function softDeleteByUserAndJob(int $userId, int $jobPostId): bool;

    public function deleteByUserAndJob(int $userId, int $jobPostId): bool;
}
