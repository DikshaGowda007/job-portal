<?php

namespace App\Modules\V1\Recruiter\Services\Shortlist\Add;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\ShortlistedCandidateDAO;
use App\Repositories\V1\ShortlistedCandidateRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private ShortlistedCandidateRepository $shortlistedCandidateRepository,
        private ShortlistedCandidateDAO $shortlistedCandidateDao,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function add(int $candidateUserId): JsonResponse
    {
        try {
            $this->saveOrReactivate($candidateUserId);

            return response()->json(CommonUtils::successResponse('Candidate shortlisted successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to shortlist candidate'));
        }
    }

    private function saveOrReactivate(int $candidateUserId): void
    {
        $existing = $this->shortlistedCandidateRepository->findByRecruiterAndCandidate($this->loggedInUserId, $candidateUserId);

        if ($existing && (int) $existing->is_deleted === CommonConstant::IS_DELETED_NO) {
            throw DataNotFoundException::withMessage('Candidate already shortlisted');
        }

        if ($existing) {
            $this->shortlistedCandidateDao->setIsDeleted(CommonConstant::IS_DELETED_NO);
            $this->shortlistedCandidateDao->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
            $this->shortlistedCandidateRepository->updateById($existing->id, $this->shortlistedCandidateDao);

            return;
        }

        $this->shortlistedCandidateDao->setRecruiterUserId($this->loggedInUserId);
        $this->shortlistedCandidateDao->setCandidateUserId($candidateUserId);
        $this->shortlistedCandidateRepository->insert($this->shortlistedCandidateDao);
    }
}
