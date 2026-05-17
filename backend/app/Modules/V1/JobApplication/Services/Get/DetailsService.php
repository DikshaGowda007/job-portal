<?php

namespace App\Modules\V1\JobApplication\Services\Get;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function get(int $applicationId): JsonResponse
    {
        try {
            $jobApplication = $this->fetchjobApplication($applicationId);

            $response = $this->formatResponse($jobApplication);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function fetchjobApplication($applicationId): Collection
    {
        $application = collect($this->jobApplicationRepository->findByIdWithJobPost($applicationId)->first());

        if ($application->isEmpty() || $application->get('user_id') !== $this->loggedInUserId) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        return $application;
    }

    private function formatResponse(Collection $application): array
    {
        $jobPost = $application->get('job_post') ?? [];

        return [
            'id' => $application->get('id'),
            'user_id' => $application->get('user_id'),
            'job_post_id' => $application->get('job_post_id'),
            'job_title' => $jobPost['title'] ?? null,
            'company_name' => $jobPost['company_name'] ?? null,
            'location' => $jobPost['location'] ?? null,
            'work_mode' => $jobPost['work_mode'] ?? null,
            'job_type' => $jobPost['job_type'] ?? null,
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
