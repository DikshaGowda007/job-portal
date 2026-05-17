<?php

namespace App\Modules\V1\SavedJob\Services\Add;

use App\Constants\CommonConstant;
use App\Repositories\DAO\V1\SavedJobDAO;
use App\Repositories\V1\SavedJobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private SavedJobRepository $savedJobRepository,
        private SavedJobDAO $savedJobDao,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function add(int $jobPostId): JsonResponse
    {
        try {
            $this->fetchSavedJob($jobPostId);
            $this->saveJob($jobPostId);

            return response()->json(CommonUtils::successResponse('Job saved successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to save job'));
        }
    }

    private function fetchSavedJob(int $jobPostId)
    {

        $existing = $this->savedJobRepository->findByUserAndJob($this->loggedInUserId, $jobPostId);
        if ($existing) {
            return response()->json(CommonUtils::errorResponse('Job already saved'));
        }

    }

    private function saveJob(int $jobPostId): void
    {
        $this->savedJobDao->setUserId($this->loggedInUserId);
        $this->savedJobDao->setJobPostId($jobPostId);

        $this->savedJobRepository->insert($this->savedJobDao);
    }
}
