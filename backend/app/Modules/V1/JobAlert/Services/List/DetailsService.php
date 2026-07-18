<?php

namespace App\Modules\V1\JobAlert\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Repositories\V1\JobAlertRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobAlertRepository $jobAlertRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function list(): JsonResponse
    {
        try {
            $alerts = $this->jobAlertRepository->fetchByUserId($this->loggedInUserId);
            $data = $this->formatResponse($alerts);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function formatResponse(Collection $alerts): array
    {
        $details = [];

        foreach ($alerts as $alert) {
            $details[] = [
                'id' => $alert->id,
                'keyword' => $alert->keyword,
                'location' => $alert->location,
                'job_category_id' => $alert->job_category_id,
                'job_type' => $alert->job_type,
                'work_mode' => $alert->work_mode,
                'experience_level' => $alert->experience_level,
                'is_active' => $alert->is_active,
                'created_at' => $alert->created_at,
            ];
        }

        return $details;
    }
}
