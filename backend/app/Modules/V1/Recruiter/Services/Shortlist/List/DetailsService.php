<?php

namespace App\Modules\V1\Recruiter\Services\Shortlist\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Repositories\V1\ShortlistedCandidateRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private ShortlistedCandidateRepository $shortlistedCandidateRepository,
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function list(int $page, int $perPage): JsonResponse
    {
        try {
            $shortlistedCandidates = $this->shortlistedCandidateRepository->fetchByRecruiterUserId($this->loggedInUserId);
            $data = $this->formatResponse($shortlistedCandidates, $page, $perPage);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function formatResponse(Collection $shortlistedCandidates, int $page, int $perPage): array
    {
        $shortlistedAtByCandidateUserId = $shortlistedCandidates->keyBy('candidate_user_id')
            ->map(fn ($shortlistedCandidate) => $shortlistedCandidate->created_at->format('Y-m-d H:i:s'));

        $profiles = $this->jobSeekerProfileRepository->findByUserIds($shortlistedAtByCandidateUserId->keys()->all());

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
                'shortlisted_at' => $shortlistedAtByCandidateUserId->get($profileData->get('user_id')),
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
