<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Get;

use App\Constants\CommonConstant;
use App\Exceptions\UserNotFoundException;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function get(): JsonResponse
    {
        try {
            $profileDetails = $this->fetchProfile();

            $data = $this->formatResponse($profileDetails);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (UserNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to fetch profile'));
        }
    }

    private function fetchProfile(): Collection
    {
        $profileDetails = collect($this->jobSeekerProfileRepository->findByUserIdWithExperiencesAndEducation($this->loggedInUserId)->first());

        if ($profileDetails->isEmpty()) {
            throw UserNotFoundException::withMessage('Profile not found');
        }

        return $profileDetails;
    }

    private function formatResponse(Collection $profileDetails): array
    {
        $transformedArray = [];

        $this->prepareBasicInfo($transformedArray, $profileDetails);
        $this->prepareLocationInfo($transformedArray, $profileDetails);
        $this->prepareJobInfo($transformedArray, $profileDetails);
        $this->prepareSalaryInfo($transformedArray, $profileDetails);
        $this->preparePreferences($transformedArray, $profileDetails);
        $this->prepareMediaInfo($transformedArray, $profileDetails);
        $this->prepareVisibilityInfo($transformedArray, $profileDetails);
        $this->prepareExperienceResponse($transformedArray, $profileDetails);
        $this->prepareEducationResponse($transformedArray, $profileDetails);

        return $transformedArray;
    }

    private function prepareBasicInfo(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'id' => $profileDetails->get('id'),
            'user_id' => $profileDetails->get('user_id'),
            'headline' => $profileDetails->get('headline'),
            'summary' => $profileDetails->get('summary'),
            'phone' => $profileDetails->get('phone'),
            'date_of_birth' => $profileDetails->get('date_of_birth'),
            'gender' => $profileDetails->get('gender'),
        ];
    }

    private function prepareLocationInfo(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'city' => $profileDetails->get('city'),
            'state' => $profileDetails->get('state'),
            'country' => $profileDetails->get('country'),
            'pincode' => $profileDetails->get('pincode'),
        ];
    }

    private function prepareJobInfo(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'current_job_title' => $profileDetails->get('current_job_title'),
            'current_company' => $profileDetails->get('current_company'),
            'total_experience_years' => $profileDetails->get('total_experience_years'),
            'total_experience_months' => $profileDetails->get('total_experience_months'),
            'skills' => $profileDetails->get('skills'),
        ];
    }

    private function prepareSalaryInfo(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'expected_salary' => $profileDetails->get('expected_salary'),
            'expected_salary_currency' => $profileDetails->get('expected_salary_currency'),
            'current_salary' => $profileDetails->get('current_salary'),
            'current_salary_currency' => $profileDetails->get('current_salary_currency'),
        ];
    }

    private function preparePreferences(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'preferred_job_types' => $profileDetails->get('preferred_job_types'),
            'preferred_work_modes' => $profileDetails->get('preferred_work_modes'),
            'preferred_locations' => $profileDetails->get('preferred_locations'),
            'notice_period' => $profileDetails->get('notice_period'),
            'immediate_joiner' => $profileDetails->get('immediate_joiner'),
        ];
    }

    private function prepareMediaInfo(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'resume_path' => $profileDetails->get('resume_path'),
            'resume_filename' => $profileDetails->get('resume_filename'),
            'resume_uploaded_at' => $profileDetails->get('resume_uploaded_at'),
            'photo_path' => $profileDetails->get('photo_path'),
            'linkedin_url' => $profileDetails->get('linkedin_url'),
            'github_url' => $profileDetails->get('github_url'),
            'portfolio_url' => $profileDetails->get('portfolio_url'),
        ];
    }

    private function prepareVisibilityInfo(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray += [
            'profile_completeness' => $profileDetails->get('profile_completeness'),
            'is_public' => $profileDetails->get('is_public'),
            'open_to_opportunities' => $profileDetails->get('open_to_opportunities'),
        ];
    }

    private function prepareExperienceResponse(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray['experiences'] = $this->formatExperiences($profileDetails->get('experiences'));
    }

    private function prepareEducationResponse(array &$transformedArray, Collection $profileDetails): void
    {
        $transformedArray['education'] = $this->formatEducation($profileDetails->get('education'));
    }

    private function formatExperiences($experiences): array
    {
        $result = [];
        foreach ($experiences as $experience) {
            $result[] = [
                'id' => $experience['id'],
                'job_title' => $experience['job_title'],
                'company_name' => $experience['company_name'],
                'employment_type' => $experience['employment_type'],
                'location' => $experience['location'],
                'work_mode' => $experience['work_mode'],
                'start_date' => $experience['start_date'],
                'end_date' => $experience['end_date'],
                'is_current' => $experience['is_current'],
                'description' => $experience['description'],
                'responsibilities' => $experience['responsibilities'],
                'achievements' => $experience['achievements'],
                'skills_used' => $experience['skills_used'],
            ];
        }

        return $result;
    }

    private function formatEducation($educations): array
    {
        $result = [];
        foreach ($educations as $education) {
            $result[] = [
                'id' => $education['id'],
                'degree' => $education['degree'],
                'field_of_study' => $education['field_of_study'],
                'institution' => $education['institution'],
                'location' => $education['location'],
                'start_year' => $education['start_year'],
                'end_year' => $education['end_year'],
                'is_current' => $education['is_current'],
                'grade' => $education['grade'],
                'percentage' => $education['percentage'],
                'cgpa' => $education['cgpa'],
                'description' => $education['description'],
                'achievements' => $education['achievements'],
            ];
        }

        return $result;
    }
}
