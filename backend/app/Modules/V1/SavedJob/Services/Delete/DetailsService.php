<?php

namespace App\Modules\V1\SavedJob\Services\Delete;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Models\SavedJob;
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
        private SavedJobDAO $savedJobDAO
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function delete(int $jobPostId): JsonResponse
    {
        try {
            $savedJob = $this->fetchSavedJob($jobPostId);
            $this->deleteSavedJob($savedJob->id);

            return response()->json(CommonUtils::successResponse('Saved job removed successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to remove saved job'));
        }
    }

    private function fetchSavedJob(int $jobPostId): SavedJob
    {
        $savedJob = $this->savedJobRepository->findByUserAndJob($this->loggedInUserId, $jobPostId);
        if (! $savedJob) {
            throw DataNotFoundException::withMessage('Saved job not found');
        }

        return $savedJob;
    }

    private function deleteSavedJob(int $id): void
    {
        $this->savedJobDAO->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->savedJobRepository->updateById($id, $this->savedJobDAO);
    }
}
