<?php

namespace App\Modules\V1\Recruiter\Services\Shortlist\Delete;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Models\ShortlistedCandidate;
use App\Repositories\DAO\V1\ShortlistedCandidateDAO;
use App\Repositories\V1\ShortlistedCandidateRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private ShortlistedCandidateRepository $shortlistedCandidateRepository,
        private ShortlistedCandidateDAO $shortlistedCandidateDao
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function delete(int $candidateUserId): JsonResponse
    {
        try {
            $shortlistedCandidate = $this->findActiveShortlistedCandidate($candidateUserId);
            $this->removeShortlistedCandidate($shortlistedCandidate->id);

            return response()->json(CommonUtils::successResponse('Candidate removed from shortlist'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to remove candidate from shortlist'));
        }
    }

    private function findActiveShortlistedCandidate(int $candidateUserId): ShortlistedCandidate
    {
        $shortlistedCandidate = $this->shortlistedCandidateRepository->findByRecruiterAndCandidate($this->loggedInUserId, $candidateUserId);

        if (! $shortlistedCandidate || (int) $shortlistedCandidate->is_deleted !== CommonConstant::IS_DELETED_NO) {
            throw DataNotFoundException::withMessage('Shortlisted candidate not found');
        }

        return $shortlistedCandidate;
    }

    private function removeShortlistedCandidate(int $id): void
    {
        $this->shortlistedCandidateDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->shortlistedCandidateRepository->updateById($id, $this->shortlistedCandidateDao);
    }
}
