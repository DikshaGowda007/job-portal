<?php

namespace App\Modules\V1\JobApplication\Services\MyApplications;

use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function myApplications(int $page = 1, int $perPage = 20): JsonResponse
    {
        try {
            $applications = $this->jobApplicationRepository->findByUserIdOrStatus(
                $this->loggedInUserId
            );

            $applications->load(['jobPost']);
            $applications = $applications->sortByDesc('created_at')->values();

            $paginated = new LengthAwarePaginator(
                $applications->forPage($page, $perPage)->values(),
                $applications->count(),
                $perPage,
                $page
            );

            if ($paginated->isEmpty()) {
                throw DataNotFoundException::withMessage();
            }

            return response()->json(CommonUtils::successDataResponse($this->formatResponse($paginated)));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function formatResponse(LengthAwarePaginator $paginated): array
    {
        return [
            'applications' => $this->formatApplications($paginated),
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
                'from' => $paginated->firstItem(),
                'to' => $paginated->lastItem(),
            ],
        ];
    }

    private function formatApplications(LengthAwarePaginator $paginated): array
    {
        $result = [];
        foreach ($paginated->items() as $application) {
            $this->formatApplication($result, collect($application->toArray()));
        }

        return $result;
    }

    private function formatApplication(array &$result, Collection $application): void
    {
        $jobPost = $application->get('job_post') ?? [];

        $result[] = [
            'id' => $application->get('id'),
            'user_id' => $application->get('user_id'),
            'job_post_id' => $application->get('job_post_id'),
            'job_title' => $jobPost['title'] ?? null,
            'company_name' => $jobPost['company_name'] ?? null,
            'resume_path' => $application->get('resume_path'),
            'cover_letter' => $application->get('cover_letter'),
            'expected_salary' => $application->get('expected_salary'),
            'expected_salary_currency' => $application->get('expected_salary_currency'),
            'notice_period' => $application->get('notice_period'),
            'experience_years' => $application->get('experience_years'),
            'status' => $application->get('status'),
            'recruiter_notes' => $application->get('recruiter_notes'),
            'reviewed_by_user_id' => $application->get('reviewed_by_user_id'),
            'reviewed_at' => $application->get('reviewed_at'),
            'created_at' => $application->get('created_at'),
            'updated_at' => $application->get('updated_at'),
        ];
    }
}
