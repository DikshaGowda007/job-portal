<?php

namespace App\Modules\V1\Job\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobConstants;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\Job\List\DetailsRequest;
use App\Modules\V1\Job\Bo\List\DetailsBo;
use App\Repositories\V1\JobRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    private DetailsBo $listDetailsBo;

    public function __construct(
        private JobRepository $jobRepository
    ) {}

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        $listDetailsBo = new DetailsBo;
        $listDetailsBo->setText($request->input('text'));
        $listDetailsBo->setPage((int) $request->input('page', 1));
        $listDetailsBo->setPerPage((int) $request->input('per_page', 20));
        $listDetailsBo->setSortBy($request->input('sort_by', 'created_at'));
        $listDetailsBo->setSortOrder($request->input('sort_order', 'desc'));
        $listDetailsBo->setWorkMode($request->input('work_mode'));
        $listDetailsBo->setJobType($request->input('job_type'));
        $listDetailsBo->setExperienceLevel($request->input('experience_level'));
        $listDetailsBo->setSalaryMin($request->input('salary_min') !== null ? (float) $request->input('salary_min') : null);
        $listDetailsBo->setSalaryMax($request->input('salary_max') !== null ? (float) $request->input('salary_max') : null);
        $listDetailsBo->setExperienceMin($request->input('experience_min') !== null ? (float) $request->input('experience_min') : null);
        $listDetailsBo->setExperienceMax($request->input('experience_max') !== null ? (float) $request->input('experience_max') : null);
        $listDetailsBo->setLocation($request->input('location'));
        $listDetailsBo->setJobCategoryId($request->input('job_category_id') !== null ? (int) $request->input('job_category_id') : null);
        $listDetailsBo->setSkills($request->input('skills'));
        $listDetailsBo->setStatus($request->input('status', JobConstants::STATUS_OPEN));

        return $listDetailsBo;
    }

    public function list(DetailsBo $bo): JsonResponse
    {
        $this->listDetailsBo = $bo;

        try {
            $filters = $this->prepareFilters();
            $jobs = $this->jobRepository->fetchWithFilters($filters);

            if ($jobs->isEmpty()) {
                throw DataNotFoundException::withMessage('Jobs not found');
            }

            $data = $this->formatResponse($jobs);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function prepareFilters(): array
    {
        return [
            'text' => $this->listDetailsBo->getText(),
            'sort_by' => $this->listDetailsBo->getSortBy(),
            'sort_order' => $this->listDetailsBo->getSortOrder(),
            'work_mode' => $this->listDetailsBo->getWorkMode(),
            'job_type' => $this->listDetailsBo->getJobType(),
            'experience_level' => $this->listDetailsBo->getExperienceLevel(),
            'salary_min' => $this->listDetailsBo->getSalaryMin(),
            'salary_max' => $this->listDetailsBo->getSalaryMax(),
            'experience_min' => $this->listDetailsBo->getExperienceMin(),
            'experience_max' => $this->listDetailsBo->getExperienceMax(),
            'location' => $this->listDetailsBo->getLocation(),
            'job_category_id' => $this->listDetailsBo->getJobCategoryId(),
            'skills' => $this->listDetailsBo->getSkills(),
            'status' => $this->listDetailsBo->getStatus(),
        ];
    }

    private function formatResponse(Collection $jobs): array
    {
        $page = $this->listDetailsBo->getPage();
        $perPage = $this->listDetailsBo->getPerPage();
        $total = $jobs->count();
        $paginated = $jobs->forPage($page, $perPage)->values();

        $details = [];
        foreach ($paginated as $job) {
            $jobData = collect($job);
            $details[] = [
                'job_id' => $jobData->get('id'),
                'title' => $jobData->get('title'),
                'company_name' => $jobData->get('company_name'),
                'location' => $jobData->get('location'),
                'work_mode' => $jobData->get('work_mode'),
                'job_type' => $jobData->get('job_type'),
                'salary_details' => $this->prepareSalaryDetails(
                    $jobData->get('salary_min'),
                    $jobData->get('salary_max'),
                    $jobData->get('salary_type'),
                    $jobData->get('salary_currency')
                ),
                'experience' => $this->prepareExperienceDetails(
                    $jobData->get('experience_min'),
                    $jobData->get('experience_max'),
                    $jobData->get('experience_level')
                ),
                'education' => $jobData->get('education'),
                'skills' => json_decode($jobData->get('skills'), true),
                'openings_count' => (int) $jobData->get('openings_count'),
                'status' => $jobData->get('status'),
                'expires_at' => $jobData->get('expires_at'),
                'created_at' => $jobData->get('created_at'),
            ];
        }

        return [
            'jobs' => $details,
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

    private function prepareSalaryDetails(?float $minSalary, ?float $maxSalary, $salaryType, ?string $currency): array
    {
        return [
            'min' => $minSalary,
            'max' => $maxSalary,
            'salary_type' => $salaryType === 'yearly' ? 'lpa' : $salaryType,
            'currency' => $currency,
        ];
    }

    private function prepareExperienceDetails(?float $minExperience, ?float $maxExperience, ?string $experienceLevel): array
    {
        return [
            'min' => $minExperience,
            'max' => $maxExperience,
            'experience_level' => $experienceLevel,
        ];
    }
}
