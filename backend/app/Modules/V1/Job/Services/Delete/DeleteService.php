<?php

namespace App\Modules\V1\Job\Services\Delete;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Repositories\V1\JobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DeleteService
{
    use AccessRightsTrait;

    public function __construct(
        private JobPostDAO $jobPostDao,
        private JobRepository $jobRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function delete(int $jobId): JsonResponse
    {
        try {
            $this->findJob($jobId);
            $this->deleteJob($jobId);

            return response()->json(CommonUtils::successResponse('Job deleted successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_DELETE_DATA));
        }
    }

    private function findJob(int $jobId): void
    {
        $jobDetails = collect($this->jobRepository->findById($jobId)->first());
        if ($jobDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Job not found');
        }
    }

    private function deleteJob(int $jobId)
    {
        $this->jobPostDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->jobPostDao->setModifiedByUserId($this->loggedInUserId);

        return $this->jobRepository->updateById($jobId, $this->jobPostDao);
    }
}
