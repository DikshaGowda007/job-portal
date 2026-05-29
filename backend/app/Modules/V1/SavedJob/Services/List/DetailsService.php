<?php

namespace App\Modules\V1\SavedJob\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Repositories\V1\SavedJobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private SavedJobRepository $savedJobRepository
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function list(): JsonResponse
    {
        try {
            $savedJobs = $this->savedJobRepository->fetchByUserId($this->loggedInUserId);
            $data = $this->formatResponse($savedJobs);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function formatResponse(Collection $savedJobs): array
    {
        $details = [];

        foreach ($savedJobs as $savedJob) {
            $job = $savedJob->jobPost;
            if (! $job || $job->is_deleted != 0) {
                continue;
            }

            $details[] = [
                'job_id' => $job->id,
                'saved_job_id' => $savedJob->id,
                'title' => $job->title,
                'company_name' => $job->company_name,
                'location' => $job->location,
                'work_mode' => $job->work_mode,
                'job_type' => $job->job_type,
                'experience' => ['experience_level' => $job->experience_level],
                'salary_details' => [
                    'min' => $job->salary_min,
                    'max' => $job->salary_max,
                    'currency' => $job->salary_currency,
                    'salary_type' => $job->salary_type,
                ],
                'created_at' => $job->created_at,
                'saved_at' => $savedJob->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $details;
    }
}
