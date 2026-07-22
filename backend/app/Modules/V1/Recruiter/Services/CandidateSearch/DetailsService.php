<?php

namespace App\Modules\V1\Recruiter\Services\CandidateSearch;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\Recruiter\CandidateSearch\DetailsRequest;
use App\Modules\V1\Recruiter\Bo\CandidateSearch\DetailsBo;
use App\Modules\V1\Recruiter\Helpers\RecruiterHelper;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    private DetailsBo $detailsBo;

    public function __construct(
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
        private RecruiterHelper $recruiterHelper,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->recruiterHelper->prepareCandidateSearchBo($detailsRequest);
    }

    public function search(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $filters = $this->prepareFilters();
            $profiles = $this->jobSeekerProfileRepository->searchPublicProfiles($filters);

            $data = $this->formatResponse($profiles);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function prepareFilters(): array
    {
        return [
            'text' => $this->detailsBo->getText(),
            'skills' => $this->detailsBo->getSkills(),
            'location' => $this->detailsBo->getLocation(),
            'experience_min' => $this->detailsBo->getExperienceMin(),
            'experience_max' => $this->detailsBo->getExperienceMax(),
            'current_job_title' => $this->detailsBo->getCurrentJobTitle(),
            'work_mode' => $this->detailsBo->getWorkMode(),
            'job_type' => $this->detailsBo->getJobType(),
            'notice_period' => $this->detailsBo->getNoticePeriod(),
            'immediate_joiner' => $this->detailsBo->getImmediateJoiner(),
            'sort_by' => $this->detailsBo->getSortBy(),
            'sort_order' => $this->detailsBo->getSortOrder(),
        ];
    }

    private function formatResponse(Collection $profiles): array
    {
        $page = $this->detailsBo->getPage();
        $perPage = $this->detailsBo->getPerPage();
        $total = $profiles->count();
        $paginated = $profiles->forPage($page, $perPage)->values();

        $candidates = [];
        foreach ($paginated as $profile) {
            $profileData = collect($profile);
            $candidates[] = [
                'user_id' => $profileData->get('user_id'),
                'headline' => $profileData->get('headline'),
                'current_job_title' => $profileData->get('current_job_title'),
                'current_company' => $profileData->get('current_company'),
                'location' => $this->formatLocation($profileData),
                'total_experience' => [
                    'years' => $profileData->get('total_experience_years'),
                    'months' => $profileData->get('total_experience_months'),
                ],
                'skills' => $profileData->get('skills'),
                'expected_salary' => [
                    'amount' => $profileData->get('expected_salary'),
                    'currency' => $profileData->get('expected_salary_currency'),
                ],
                'notice_period' => $profileData->get('notice_period'),
                'immediate_joiner' => $profileData->get('immediate_joiner'),
                'preferred_work_modes' => $profileData->get('preferred_work_modes'),
                'photo_path' => $profileData->get('photo_path'),
                'profile_completeness' => $profileData->get('profile_completeness'),
                'updated_at' => $profileData->get('updated_at'),
            ];
        }

        return [
            'candidates' => $candidates,
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

    private function formatLocation(Collection $profileData): ?string
    {
        $parts = array_filter([
            $profileData->get('city'),
            $profileData->get('state'),
            $profileData->get('country'),
        ]);

        return $parts ? implode(', ', $parts) : null;
    }
}
