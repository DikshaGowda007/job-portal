<?php

namespace App\Modules\V1\Admin\Services\Dashboard;

use App\Constants\CommonConstant;
use App\Http\Requests\V1\Admin\DashboardRequest;
use App\Modules\V1\Admin\Bo\DashboardBo;
use App\Modules\V1\Admin\Helpers\AdminHelper;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\JobRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    private DashboardBo $dashboardBo;

    public function __construct(
        private AdminHelper $adminHelper,
        private UserRepository $userRepository,
        private JobRepository $jobRepository,
        private JobApplicationRepository $jobApplicationRepository,
    ) {}

    public function getDashboardStats(DashboardBo $bo): JsonResponse
    {
        $this->dashboardBo = $bo;
        try {
            $startDate = $this->getStartDate();
            $endDate = $this->getEndDate();

            $stats = [
                'total_users' => $this->fetchTotalUsers(),
                'users_by_role' => $this->fetchUsersByRole(),
                'total_jobs' => $this->fetchTotalJobs(),
                'jobs_by_status' => $this->fetchJobsByStatus(),
                'total_applications' => $this->fetchTotalApplications(),
                'applications_by_status' => $this->fetchApplicationsByStatus(),
                'recent_registrations' => $this->fetchRecentRegistrations($startDate, $endDate),
                'recent_applications' => $this->fetchRecentApplications($startDate, $endDate),
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ],
            ];

            return response()->json(CommonUtils::successDataResponse([
                'message' => 'Dashboard statistics retrieved successfully',
                'stats' => $stats,
            ]));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_ERROR);

            return response()->json(CommonUtils::errorResponse('Failed to retrieve dashboard statistics'));
        }
    }

    public function prepareBo(DashboardRequest $dashboardRequest): DashboardBo
    {
        return $this->adminHelper->prepareDashboardBo($dashboardRequest);
    }

    private function getStartDate(): Carbon
    {
        return $this->dashboardBo->getStartDate()
            ? Carbon::parse($this->dashboardBo->getStartDate())
            : Carbon::now()->subDays(30);
    }

    private function getEndDate(): Carbon
    {
        $date = $this->dashboardBo->getEndDate()
            ? Carbon::parse($this->dashboardBo->getEndDate())
            : Carbon::now();

        return $date->endOfDay();
    }

    private function fetchTotalUsers(): int
    {
        return $this->userRepository->findAll()->count();
    }

    private function fetchUsersByRole(): array
    {
        return $this->userRepository->findAll()
            ->groupBy('user_type')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    private function fetchTotalJobs(): int
    {
        return $this->jobRepository->findAll()->count();
    }

    private function fetchJobsByStatus(): array
    {
        return $this->jobRepository->findAll()
            ->groupBy('status')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    private function fetchTotalApplications(): int
    {
        return $this->jobApplicationRepository->findAll()->count();
    }

    private function fetchApplicationsByStatus(): array
    {
        return $this->jobApplicationRepository->findAll()
            ->groupBy('status')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    private function fetchRecentRegistrations(Carbon $startDate, Carbon $endDate): int
    {
        return $this->userRepository->findByCreatedAt($startDate, $endDate)->count();
    }

    private function fetchRecentApplications(Carbon $startDate, Carbon $endDate): int
    {
        return $this->jobApplicationRepository->findByCreatedAtRange($startDate, $endDate)->count();
    }
}
