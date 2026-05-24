<?php

namespace App\Modules\V1\Recruiter\Services\MyApplications;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\Recruiter\MyApplications\DetailsRequest;
use App\Modules\V1\Recruiter\Bo\MyApplications\DetailsBo;
use App\Modules\V1\Recruiter\Helpers\RecruiterHelper;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    private DetailsBo $detailsBo;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
        private RecruiterHelper $recruiterHelper,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function myApplications(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $filters = $this->prepareFilters();
            $applicationDetails = $this->fetchJobs($filters);

            $data = $this->formatResponse($applicationDetails);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->recruiterHelper->prepareMyApplicationsBo($detailsRequest);
    }

    private function fetchJobs(array $filters)
    {

        $applicationDetails = $this->jobApplicationRepository->fetchByRecruiterWithFilters($this->loggedInUserId, $filters);

        if ($applicationDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('No applications found');
        }

        return $applicationDetails;
    }

    private function prepareFilters(): array
    {
        return [
            'text' => $this->detailsBo->getText(),
            'job_post_id' => $this->detailsBo->getJobPostId(),
            'status' => $this->detailsBo->getStatus(),
            'date_from' => $this->detailsBo->getDateFrom(),
            'date_to' => $this->detailsBo->getDateTo()
                ? $this->detailsBo->getDateTo().' 23:59:59'
                : null,
            'sort_by' => $this->detailsBo->getSortBy(),
            'sort_order' => $this->detailsBo->getSortOrder(),
        ];
    }

    private function formatResponse(Collection $applications): array
    {
        $page = $this->detailsBo->getPage();
        $perPage = $this->detailsBo->getPerPage();
        $total = $applications->count();
        $paginated = $applications->forPage($page, $perPage)->values();

        $result = [];
        foreach ($paginated as $application) {
            $item = collect($application);
            $result[] = [
                'application_id' => $item->get('id'),
                'job_post_id' => $item->get('job_post_id'),
                'job_title' => $item->get('job_title'),
                'company_name' => $item->get('company_name'),
                'applicant' => [
                    'user_id' => $item->get('user_id'),
                    'first_name' => $item->get('first_name'),
                    'last_name' => $item->get('last_name'),
                    'email' => $item->get('email'),
                ],
                'expected_salary' => $item->get('expected_salary'),
                'expected_salary_currency' => $item->get('expected_salary_currency'),
                'notice_period' => $item->get('notice_period'),
                'experience_years' => $item->get('experience_years'),
                'status' => $item->get('status'),
                'resume_path' => $item->get('resume_path'),
                'cover_letter' => $item->get('cover_letter'),
                'recruiter_notes' => $item->get('recruiter_notes'),
                'viewed' => (bool) $item->get('viewed'),
                'reviewed_at' => $item->get('reviewed_at'),
                'created_at' => $item->get('created_at'),
            ];
        }

        return [
            'applications' => $result,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage),
                'from' => $total > 0 ? ($page - 1) * $perPage + 1 : 0,
                'to' => min($page * $perPage, $total),
            ],
        ];
    }
}
