<?php

namespace App\Repositories\MySql\V1;

use App\Models\ShortlistedCandidate;
use App\Repositories\DAO\V1\ShortlistedCandidateDAO;
use App\Repositories\V1\ShortlistedCandidateRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ShortlistedCandidateRepositoryImpl implements ShortlistedCandidateRepository
{
    public function insert(ShortlistedCandidateDAO $shortlistedCandidateDAO): ShortlistedCandidate
    {
        $shortlistedCandidateDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return ShortlistedCandidate::create($shortlistedCandidateDAO->toArray());
    }

    public function findByRecruiterAndCandidate(int $recruiterUserId, int $candidateUserId): ?ShortlistedCandidate
    {
        return ShortlistedCandidate::where('recruiter_user_id', $recruiterUserId)
            ->where('candidate_user_id', $candidateUserId)
            ->first();
    }

    public function fetchByRecruiterUserId(int $recruiterUserId): Collection
    {
        return ShortlistedCandidate::where('recruiter_user_id', $recruiterUserId)
            ->where('is_deleted', 0)
            ->get();
    }

    public function updateById(int $id, ShortlistedCandidateDAO $shortlistedCandidateDAO): bool|int
    {
        return ShortlistedCandidate::where('id', $id)
            ->update($shortlistedCandidateDAO->toArray());
    }
}
