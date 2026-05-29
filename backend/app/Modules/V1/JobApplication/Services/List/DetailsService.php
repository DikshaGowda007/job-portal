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
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function list(int $page = 1, int $perPage = 20, ?string $status = null, ?string $text = null, ?int $jobPostId = null): JsonResponse
    {
        try {
            $applications = $this->findApplications($status, $text, $jobPostId);
            $data = $this->formatResponse($applications, $page, $perPage);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function findApplications(?string $status, ?string $text, ?int $jobPostId): Collection
    {
        if ($this->isAdminOrSubAdmin()) {
            $applications = $this->jobApplicationRepository->findAllWithFilters([
                'status' => $status,
                'text' => $text,
            ]);
        } else {
            $applications = $this->jobApplicationRepository->fetchByRecruiterWithFilters(
                $this->loggedInUserId,
                [
                    'status' => $status,
                    'text' => $text,
                    'job_post_id' => $jobPostId,
                    'sort_by' => 'job_applications.created_at',
                    'sort_order' => 'desc',
                ]
            );
        }

        if ($applications->isEmpty()) {
            throw DataNotFoundException::withMessage();
        }

        return $applications;
    }

    private function isAdminOrSubAdmin(): bool
    {
        return in_array($this->loggedInUserRole, [UserConstant::USER_ROLE_ADMIN, UserConstant::USER_ROLE_SUB_ADMIN], true);
    }

    private function formatResponse(Collection $applications, int $page, int $perPage): array
    {
        $total = $applications->count();
        $paginated = $applications->forPage($page, $perPage)->values();

        return [
            'applications' => $paginated->map(fn ($app) => $this->formatApplication(collect($app)))->all(),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => max(1, (int) ceil($total / $perPage)),
                'from' => $total > 0 ? ($page - 1) * $perPage + 1 : 0,
                'to' => min($page * $perPage, $total),
            ],
        ];
    }

    private function formatApplication(Collection $app): array
    {
        $firstName = $app->get('first_name') ?? '';
        $lastName = $app->get('last_name') ?? '';
        $applicantName = trim("$firstName $lastName") ?: null;

        $recruiterFirst = $app->get('recruiter_first_name') ?? '';
        $recruiterLast = $app->get('recruiter_last_name') ?? '';
        $recruiterName = trim("$recruiterFirst $recruiterLast") ?: null;

        return [
            'id' => $app->get('id'),
            'user_id' => $app->get('user_id'),
            'job_post_id' => $app->get('job_post_id'),
            'applicant_name' => $applicantName,
            'applicant_email' => $app->get('email'),
            'job_title' => $app->get('job_title'),
            'company_name' => $app->get('company_name'),
            'recruiter_name' => $recruiterName,
            'recruiter_email' => $app->get('recruiter_email'),
            'resume_path' => $app->get('resume_path'),
            'cover_letter' => $app->get('cover_letter'),
            'expected_salary' => $app->get('expected_salary'),
            'expected_salary_currency' => $app->get('expected_salary_currency'),
            'notice_period' => $app->get('notice_period'),
            'experience_years' => $app->get('experience_years'),
            'status' => $app->get('status'),
            'recruiter_notes' => $app->get('recruiter_notes'),
            'viewed' => (bool) $app->get('viewed'),
            'reviewed_at' => $app->get('reviewed_at'),
            'created_at' => $app->get('created_at'),
            'updated_at' => $app->get('updated_at'),
        ];
    }
}
