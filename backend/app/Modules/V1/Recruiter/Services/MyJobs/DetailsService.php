<?php

namespace App\Modules\V1\Recruiter\Services\MyJobs;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\InvalidJobException;
use App\Http\Requests\V1\Recruiter\MyJobs\DetailsRequest;
use App\Modules\V1\Recruiter\Bo\MyJobs\DetailsBo;
use App\Modules\V1\Recruiter\Helpers\RecruiterHelper;
use App\Repositories\V1\JobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    private DetailsBo $detailsBo;

    public function __construct(
        private JobRepository $jobRepository,
        private RecruiterHelper $recruiterHelper,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function myJobs(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $filters = $this->prepareFilters();

            $jobDetails = $this->fetchJobs($filters);

            $data = $this->formatResponse($jobDetails);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (InvalidJobException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->recruiterHelper->prepareMyJobsBo($detailsRequest);
    }

    private function fetchJobs(array $filters)
    {
        $jobDetails = $this->jobRepository->fetchByRecruiterWithFilters($this->loggedInUserId, $filters);

        if ($jobDetails->isEmpty()) {
            throw InvalidJobException::withMessage('No jobs found');
        }

        return $jobDetails;
    }

    private function prepareFilters(): array
    {
        return [
            'text' => $this->detailsBo->getText(),
            'sort_by' => $this->detailsBo->getSortBy(),
            'sort_order' => $this->detailsBo->getSortOrder(),
            'status' => $this->detailsBo->getStatus(),
            'work_mode' => $this->detailsBo->getWorkMode(),
            'job_type' => $this->detailsBo->getJobType(),
            'experience_level' => $this->detailsBo->getExperienceLevel(),
            'salary_min' => $this->detailsBo->getSalaryMin(),
            'salary_max' => $this->detailsBo->getSalaryMax(),
            'location' => $this->detailsBo->getLocation(),
            'job_category_id' => $this->detailsBo->getJobCategoryId(),
        ];
    }

    private function formatResponse(Collection $jobs): array
    {
        $page = $this->detailsBo->getPage();
        $perPage = $this->detailsBo->getPerPage();
        $total = $jobs->count();
        $paginated = $jobs->forPage($page, $perPage)->values();

        $result = [];
        foreach ($paginated as $job) {
            $jobData = collect($job);
            $result[] = [
                'job_id' => $jobData->get('id'),
                'title' => $jobData->get('title'),
                'company_name' => $jobData->get('company_name'),
                'location' => $jobData->get('location'),
                'work_mode' => $jobData->get('work_mode'),
                'job_type' => $jobData->get('job_type'),
                'salary_details' => [
                    'min' => $jobData->get('salary_min'),
                    'max' => $jobData->get('salary_max'),
                    'salary_type' => $jobData->get('salary_type') === 'yearly' ? 'lpa' : $jobData->get('salary_type'),
                    'currency' => $jobData->get('salary_currency'),
                ],
                'experience' => [
                    'min' => $jobData->get('experience_min'),
                    'max' => $jobData->get('experience_max'),
                    'experience_level' => $jobData->get('experience_level'),
                ],
                'education' => $jobData->get('education'),
                'skills' => json_decode($jobData->get('skills'), true),
                'openings_count' => (int) $jobData->get('openings_count'),
                'applications_count' => (int) $jobData->get('applications_count'),
                'status' => $jobData->get('status'),
                'expires_at' => $jobData->get('expires_at'),
                'created_at' => $jobData->get('created_at'),
                'updated_at' => $jobData->get('updated_at'),
            ];
        }

        return [
            'jobs' => $result,
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
