<?php

namespace App\Modules\V1\Job\Services\Get;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobRepository;
use App\Utils\CommonUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobRepository $jobRepository
    ) {}

    public function get(?string $text): JsonResponse
    {
        try {
            $jobList = $this->listJobs($text);
            $data = $this->formatResponse($jobList);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);
            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function listJobs(?string $text): Collection
    {
        $jobData = $this->jobRepository->fetch($text);
        if ($jobData->isEmpty()) {
            throw DataNotFoundException::withMessage('Jobs not found');
        }
        return $jobData->sortByDesc('id');
    }

    private function formatResponse(Collection $jobList): array
    {
        $details = [];

        foreach ($jobList as $jobs) {
            $job = collect($jobs);
            $details[] = [
                'job_id' => $job->get('id'),
                'title' => $job->get('title'),
                'company_name' => $job->get('company_name'),
                'location' => $job->get('location'),
                'work_mode' => $job->get('work_mode'),
                'job_type' => $job->get('job_type'),
                'salary_details' => $this->prepareSalaryDetails($job->get('salary_min'), $job->get('salary_max'), $job->get('salary_type'), $job->get('salary_currency')),
                'experience' => $this->prepareExperienceDetails($job->get('experience_min'), $job->get('experience_max'), $job->get('experience_level')),
                'education' => $job->get('education'),
                'skills' => json_decode($job->get('skills'), true),
                'openings_count' => (int) $job->get('openings_count'),
                'status' => (int) $job->get('status'),
                'expires_at' => $job->get('expires_at'),
                'created_at' => $job->get('created_at'),
            ];
        }

        return $details;
    }

    private function prepareSalaryDetails(?float $minSalary, ?float $maxSalary, $salaryType, string $currency)
    {
        return [
            'min' => $minSalary,
            'max' => $maxSalary,
            'salary_type' => $salaryType === 'yearly' ? 'lpa' : $salaryType,
            'currency' => $currency
        ];

    }

    private function prepareExperienceDetails(?float $minExperience, ?float $maxExperience, ?string $experienceLevel)
    {
        return [
            'min' => $minExperience,
            'max' => $maxExperience,
            'experience_level' => $experienceLevel
        ];
    }
}