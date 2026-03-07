<?php

namespace App\Modules\V1\JobApplication\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\UserConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection as SupportCollection;

class DetailsService
{
    use AccessRightsTrait;

    private ?Collection $jobApplication = null;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function list(?int $jobPostId = null, ?string $status = null): JsonResponse
    {
        try {
            $this->fetchApplications($jobPostId, $status);
            $data = $this->formatResponse();

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (DataNotFoundException $e) {

            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    public function fetchApplications(?int $jobPostId = null, ?string $status = null)
    {
        if ($this->loggedInUserRole == UserConstant::USER_ROLE_JOB_SEEKER) {
            $this->jobApplication = $this->jobApplicationRepository->findByUserIdOrStatus($this->loggedInUserId, $status);
            if ($this->jobApplication->isEmpty()) {
                throw DataNotFoundException::withMessage();
            }

            return;
        }

        $this->jobApplication = $this->jobApplicationRepository->findByJobPostIdOrStatus($jobPostId, $status);
        if ($this->jobApplication->isEmpty()) {
            throw DataNotFoundException::withMessage();
        }
    }

    private function formatResponse()
    {
        $transformedArray = [];
        foreach ($this->jobApplication as $value) {
            $this->formatJob($transformedArray, collect($value));
        }

        return $transformedArray;
    }

    private function formatJob(array &$transformedArray, SupportCollection $jobApplication)
    {
        $transformedArray[] = [
            'id' => $jobApplication->get('id'),
            'user_id' => $jobApplication->get('user_id'),
            'job_post_id' => $jobApplication->get('job_post_id'),
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
