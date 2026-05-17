<?php

namespace App\Modules\V1\JobApplication\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\UserConstant;
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

    public function list(?int $jobPostId = null, ?string $status = null, int $page = 1, int $perPage = 20): JsonResponse
    {
        try {
            $seekerResponse = $this->fetchJobs($page, $perPage);

            if ($seekerResponse !== null) {
                return $seekerResponse;
            }

            return response()->json(CommonUtils::successDataResponse([]));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function fetchJobs(int $page = 1, int $perPage = 20): ?JsonResponse
    {
        if ($this->loggedInUserRole == UserConstant::USER_ROLE_JOB_SEEKER) {
            $jobApplicationDetails = $this->jobApplicationRepository->fetchByUserIdAndStatus($this->loggedInUserId, CommonConstant::STATUS_ACTIVE);

            $jobApplicationDetails->load(['jobPost']);
            $jobApplicationDetails = $jobApplicationDetails->sortByDesc('created_at')->values();

            $paginatedJobs = new LengthAwarePaginator(
                $jobApplicationDetails->forPage($page, $perPage)->values(),
                $jobApplicationDetails->count(),
                $perPage,
                $page
            );

            if ($paginatedJobs->isEmpty()) {
                throw DataNotFoundException::withMessage();
            }

            return response()->json([
                'status' => CommonConstant::SUCCESS,
                'data' => $this->formatPaginatedJobResponse($paginatedJobs),
                'pagination' => [
                    'current_page' => $paginatedJobs->currentPage(),
                    'per_page' => $paginatedJobs->perPage(),
                    'total' => $paginatedJobs->total(),
                    'last_page' => $paginatedJobs->lastPage(),
                    'from' => $paginatedJobs->firstItem(),
                    'to' => $paginatedJobs->lastItem(),
                ],
            ]);
        }

        return null;
    }

    private function formatPaginatedJobResponse(LengthAwarePaginator $paginatedJobs): array
    {
        $applications = [];
        foreach ($paginatedJobs->items() as $value) {
            $this->formatJob($applications, collect($value->toArray()));
        }

        return $applications;
    }

    private function formatJob(array &$transformedArray, Collection $jobApplication)
    {
        $jobPost = $jobApplication->get('job_post') ?? [];

        $transformedArray[] = [
            'id' => $jobApplication->get('id'),
            'user_id' => $jobApplication->get('user_id'),
            'job_post_id' => $jobApplication->get('job_post_id'),
            'job_title' => $jobPost['title'] ?? null,
            'company_name' => $jobPost['company_name'] ?? null,
            'resume_path' => $jobApplication->get('resume_path'),
            'cover_letter' => $jobApplication->get('cover_letter'),
            'expected_salary' => $jobApplication->get('expected_salary'),
            'expected_salary_currency' => $jobApplication->get('expected_salary_currency'),
            'notice_period' => $jobApplication->get('notice_period'),
            'experience_years' => $jobApplication->get('experience_years'),
            'status' => $jobApplication->get('status'),
            'recruiter_notes' => $jobApplication->get('recruiter_notes'),
            'reviewed_by_user_id' => $jobApplication->get('reviewed_by_user_id'),
            'reviewed_at' => $jobApplication->get('reviewed_at'),
            'created_at' => $jobApplication->get('created_at'),
            'updated_at' => $jobApplication->get('updated_at'),
            'is_deleted' => $jobApplication->get('is_deleted'),
        ];
    }
}
