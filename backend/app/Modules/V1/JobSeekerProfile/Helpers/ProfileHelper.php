<?php

namespace App\Modules\V1\JobSeekerProfile\Helpers;

use App\Http\Requests\V1\JobSeekerProfile\Update\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Update\DetailsBo;
use App\Repositories\DAO\V1\JobSeekerProfileDAO;
use App\Traits\V1\AccessRightsTrait;

class ProfileHelper
{
    use AccessRightsTrait;

    public function __construct()
    {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        $detailsBo = new DetailsBo;
        $detailsBo->setUserId($this->loggedInUserId);

        if ($detailsRequest->has('headline')) {
            $detailsBo->setHeadline($detailsRequest->input('headline'));
        }
        if ($detailsRequest->has('summary')) {
            $detailsBo->setSummary($detailsRequest->input('summary'));
        }
        if ($detailsRequest->has('phone')) {
            $detailsBo->setPhone($detailsRequest->input('phone'));
        }
        if ($detailsRequest->has('date_of_birth')) {
            $detailsBo->setDateOfBirth($detailsRequest->input('date_of_birth'));
        }
        if ($detailsRequest->has('gender')) {
            $detailsBo->setGender($detailsRequest->input('gender'));
        }
        if ($detailsRequest->has('city')) {
            $detailsBo->setCity($detailsRequest->input('city'));
        }
        if ($detailsRequest->has('state')) {
            $detailsBo->setState($detailsRequest->input('state'));
        }
        if ($detailsRequest->has('country')) {
            $detailsBo->setCountry($detailsRequest->input('country'));
        }
        if ($detailsRequest->has('pincode')) {
            $detailsBo->setPincode($detailsRequest->input('pincode'));
        }
        if ($detailsRequest->has('current_job_title')) {
            $detailsBo->setCurrentJobTitle($detailsRequest->input('current_job_title'));
        }
        if ($detailsRequest->has('current_company')) {
            $detailsBo->setCurrentCompany($detailsRequest->input('current_company'));
        }
        if ($detailsRequest->has('total_experience_years')) {
            $detailsBo->setTotalExperienceYears($detailsRequest->input('total_experience_years'));
        }
        if ($detailsRequest->has('total_experience_months')) {
            $detailsBo->setTotalExperienceMonths($detailsRequest->input('total_experience_months'));
        }
        if ($detailsRequest->has('expected_salary')) {
            $detailsBo->setExpectedSalary($detailsRequest->input('expected_salary'));
        }
        if ($detailsRequest->has('expected_salary_currency')) {
            $detailsBo->setExpectedSalaryCurrency($detailsRequest->input('expected_salary_currency'));
        }
        if ($detailsRequest->has('current_salary')) {
            $detailsBo->setCurrentSalary($detailsRequest->input('current_salary'));
        }
        if ($detailsRequest->has('current_salary_currency')) {
            $detailsBo->setCurrentSalaryCurrency($detailsRequest->input('current_salary_currency'));
        }
        if ($detailsRequest->has('preferred_job_types')) {
            $detailsBo->setPreferredJobTypes($detailsRequest->input('preferred_job_types'));
        }
        if ($detailsRequest->has('preferred_work_modes')) {
            $detailsBo->setPreferredWorkModes($detailsRequest->input('preferred_work_modes'));
        }
        if ($detailsRequest->has('preferred_locations')) {
            $detailsBo->setPreferredLocations($detailsRequest->input('preferred_locations'));
        }
        if ($detailsRequest->has('notice_period')) {
            $detailsBo->setNoticePeriod($detailsRequest->input('notice_period'));
        }
        if ($detailsRequest->has('immediate_joiner')) {
            $detailsBo->setImmediateJoiner($detailsRequest->input('immediate_joiner'));
        }
        if ($detailsRequest->has('skills')) {
            $detailsBo->setSkills($detailsRequest->input('skills'));
        }
        if ($detailsRequest->has('linkedin_url')) {
            $detailsBo->setLinkedinUrl($detailsRequest->input('linkedin_url'));
        }
        if ($detailsRequest->has('github_url')) {
            $detailsBo->setGithubUrl($detailsRequest->input('github_url'));
        }
        if ($detailsRequest->has('portfolio_url')) {
            $detailsBo->setPortfolioUrl($detailsRequest->input('portfolio_url'));
        }
        if ($detailsRequest->has('is_public')) {
            $detailsBo->setIsPublic($detailsRequest->input('is_public'));
        }
        if ($detailsRequest->has('open_to_opportunities')) {
            $detailsBo->setOpenToOpportunities($detailsRequest->input('open_to_opportunities'));
        }

        return $detailsBo;
    }

    public function prepareDao(DetailsBo $detailsBo): JobSeekerProfileDAO
    {
        $jobSeekerProfileDao = new JobSeekerProfileDAO;

        if (! is_null($detailsBo->getHeadline())) {
            $jobSeekerProfileDao->setHeadline($detailsBo->getHeadline());
        }
        if (! is_null($detailsBo->getSummary())) {
            $jobSeekerProfileDao->setSummary($detailsBo->getSummary());
        }
        if (! is_null($detailsBo->getPhone())) {
            $jobSeekerProfileDao->setPhone($detailsBo->getPhone());
        }
        if (! is_null($detailsBo->getDateOfBirth())) {
            $jobSeekerProfileDao->setDateOfBirth($detailsBo->getDateOfBirth());
        }
        if (! is_null($detailsBo->getGender())) {
            $jobSeekerProfileDao->setGender($detailsBo->getGender());
        }
        if (! is_null($detailsBo->getCity())) {
            $jobSeekerProfileDao->setCity($detailsBo->getCity());
        }
        if (! is_null($detailsBo->getState())) {
            $jobSeekerProfileDao->setState($detailsBo->getState());
        }
        if (! is_null($detailsBo->getCountry())) {
            $jobSeekerProfileDao->setCountry($detailsBo->getCountry());
        }
        if (! is_null($detailsBo->getPincode())) {
            $jobSeekerProfileDao->setPincode($detailsBo->getPincode());
        }
        if (! is_null($detailsBo->getCurrentJobTitle())) {
            $jobSeekerProfileDao->setCurrentJobTitle($detailsBo->getCurrentJobTitle());
        }
        if (! is_null($detailsBo->getCurrentCompany())) {
            $jobSeekerProfileDao->setCurrentCompany($detailsBo->getCurrentCompany());
        }
        if (! is_null($detailsBo->getTotalExperienceYears())) {
            $jobSeekerProfileDao->setTotalExperienceYears($detailsBo->getTotalExperienceYears());
        }
        if (! is_null($detailsBo->getTotalExperienceMonths())) {
            $jobSeekerProfileDao->setTotalExperienceMonths($detailsBo->getTotalExperienceMonths());
        }
        if (! is_null($detailsBo->getExpectedSalary())) {
            $jobSeekerProfileDao->setExpectedSalary($detailsBo->getExpectedSalary());
        }
        if (! is_null($detailsBo->getExpectedSalaryCurrency())) {
            $jobSeekerProfileDao->setExpectedSalaryCurrency($detailsBo->getExpectedSalaryCurrency());
        }
        if (! is_null($detailsBo->getCurrentSalary())) {
            $jobSeekerProfileDao->setCurrentSalary($detailsBo->getCurrentSalary());
        }
        if (! is_null($detailsBo->getCurrentSalaryCurrency())) {
            $jobSeekerProfileDao->setCurrentSalaryCurrency($detailsBo->getCurrentSalaryCurrency());
        }
        if (! is_null($detailsBo->getPreferredJobTypes())) {
            $jobSeekerProfileDao->setPreferredJobTypes(json_encode($detailsBo->getPreferredJobTypes()));
        }
        if (! is_null($detailsBo->getPreferredWorkModes())) {
            $jobSeekerProfileDao->setPreferredWorkModes(json_encode($detailsBo->getPreferredWorkModes()));
        }
        if (! is_null($detailsBo->getPreferredLocations())) {
            $jobSeekerProfileDao->setPreferredLocations(json_encode($detailsBo->getPreferredLocations()));
        }
        if (! is_null($detailsBo->getNoticePeriod())) {
            $jobSeekerProfileDao->setNoticePeriod($detailsBo->getNoticePeriod());
        }
        if (! is_null($detailsBo->getImmediateJoiner())) {
            $jobSeekerProfileDao->setImmediateJoiner($detailsBo->getImmediateJoiner());
        }
        if (! is_null($detailsBo->getSkills())) {
            $jobSeekerProfileDao->setSkills(json_encode($detailsBo->getSkills()));
        }
        if (! is_null($detailsBo->getLinkedinUrl())) {
            $jobSeekerProfileDao->setLinkedinUrl($detailsBo->getLinkedinUrl());
        }
        if (! is_null($detailsBo->getGithubUrl())) {
            $jobSeekerProfileDao->setGithubUrl($detailsBo->getGithubUrl());
        }
        if (! is_null($detailsBo->getPortfolioUrl())) {
            $jobSeekerProfileDao->setPortfolioUrl($detailsBo->getPortfolioUrl());
        }
        if (! is_null($detailsBo->getIsPublic())) {
            $jobSeekerProfileDao->setIsPublic($detailsBo->getIsPublic());
        }
        if (! is_null($detailsBo->getOpenToOpportunities())) {
            $jobSeekerProfileDao->setOpenToOpportunities($detailsBo->getOpenToOpportunities());
        }

        return $jobSeekerProfileDao;
    }

    public function prepareJobSeekerProfileDao(int $userId): JobSeekerProfileDAO
    {
        $jobSeekerProfileDao = new JobSeekerProfileDAO;
        $jobSeekerProfileDao->setUserId($userId);
        $jobSeekerProfileDao->setProfileCompleteness(0);
        $jobSeekerProfileDao->setIsPublic(true);
        $jobSeekerProfileDao->setOpenToOpportunities(true);

        return $jobSeekerProfileDao;
    }

    public function calculateProfileCompleteness(array $profileData): int
    {
        $fields = [
            'headline' => 5,
            'summary' => 10,
            'phone' => 5,
            'city' => 5,
            'country' => 5,
            'current_job_title' => 5,
            'total_experience_years' => 5,
            'expected_salary' => 5,
            'skills' => 15,
            'resume_path' => 20,
            'photo_path' => 5,
            'linkedin_url' => 5,
            'experiences' => 5,
            'education' => 5,
        ];

        $completeness = 0;
        foreach ($fields as $field => $weight) {
            if (! empty($profileData[$field])) {
                $completeness += $weight;
            }
        }

        return min($completeness, 100);
    }
}
