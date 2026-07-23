<?php

namespace App\Repositories\V1;

use App\Models\ShortlistedCandidate;
use App\Repositories\DAO\V1\ShortlistedCandidateDAO;
use Illuminate\Database\Eloquent\Collection;

interface ShortlistedCandidateRepository
{
    public function insert(ShortlistedCandidateDAO $shortlistedCandidateDAO): ShortlistedCandidate;

    public function findByRecruiterAndCandidate(int $recruiterUserId, int $candidateUserId): ?ShortlistedCandidate;

    public function fetchByRecruiterUserId(int $recruiterUserId): Collection;

    public function updateById(int $id, ShortlistedCandidateDAO $shortlistedCandidateDAO): bool|int;
}
