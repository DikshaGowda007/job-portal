<?php

namespace App\Modules\V1\Recruiter\Services\Dashboard;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobApplicationConstants;
use App\Constants\JobConstants;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\JobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobRepository $jobRepository,
        private JobApplicationRepository $jobApplicationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function dashboard(): JsonResponse
    {
        try {
            $recruiterId = $this->loggedInUserId;

            $data = [
                'jobs' => $this->fetchJobStats($recruiterId),
                'applications' => $this->fetchApplicationStats($recruiterId),
            ];

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function fetchJobStats(int $recruiterId): array
    {
        $status = $this->jobRepository->findByRecruiterId($recruiterId)
            ->groupBy('status')
            ->map(fn ($group) => $group->count())
            ->toArray();

        return [
            'total' => array_sum($status),
            'open' => (int) ($status[JobConstants::STATUS_OPEN] ?? 0),
            'closed' => (int) ($status[JobConstants::STATUS_CLOSED] ?? 0),
            'expired' => (int) ($status[JobConstants::STATUS_EXPIRED] ?? 0),
        ];
    }

    private function fetchApplicationStats(int $recruiterId): array
    {
        $status = $this->jobApplicationRepository->findByRecruiterId($recruiterId)
            ->groupBy('status')
            ->map(fn ($group) => $group->count())
            ->toArray();

        return [
            'total' => array_sum($status),
            'pending' => (int) ($status[JobApplicationConstants::STATUS_PENDING] ?? 0),
            'reviewed' => (int) ($status[JobApplicationConstants::STATUS_REVIEWED] ?? 0),
            'shortlisted' => (int) ($status[JobApplicationConstants::STATUS_SHORTLISTED] ?? 0),
            'interview' => (int) ($status[JobApplicationConstants::STATUS_INTERVIEW] ?? 0),
            'offered' => (int) ($status[JobApplicationConstants::STATUS_OFFERED] ?? 0),
            'hired' => (int) ($status[JobApplicationConstants::STATUS_HIRED] ?? 0),
            'rejected' => (int) ($status[JobApplicationConstants::STATUS_REJECTED] ?? 0),
        ];
    }
}
