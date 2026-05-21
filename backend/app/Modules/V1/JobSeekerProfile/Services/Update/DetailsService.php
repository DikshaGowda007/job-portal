<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Update;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\JobSeekerProfile\Update\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Update\DetailsBo;
use App\Modules\V1\JobSeekerProfile\Helpers\ProfileHelper;
use App\Repositories\DAO\V1\JobSeekerProfileDAO;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    public function __construct(
        private ProfileHelper $profileHelper,
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
        private JobSeekerProfileDAO $jobSeekerProfileDAO,
        private DetailsBo $detailsBo,
    ) {}

    public function update(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $this->ensureProfileExists();
            $this->updateProfile();
            $this->recalculateCompleteness();

            return response()->json(CommonUtils::successResponse('Profile updated successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->profileHelper->prepareBo($detailsRequest);
    }

    private function fetchProfile(): Collection
    {
        return collect($this->jobSeekerProfileRepository->findByUserId($this->detailsBo->getUserId())->first());
    }

    private function fetchProfileWithDetails(): Collection
    {
        return collect($this->jobSeekerProfileRepository->findByUserIdWithExperiencesAndEducation($this->detailsBo->getUserId())->first());
    }

    private function ensureProfileExists(): void
    {
        $profileDetails = $this->fetchProfile();

        if ($profileDetails->isEmpty()) {
            $jobSeekerProfileDAO = $this->profileHelper->prepareJobSeekerProfileDAO($this->detailsBo->getUserId());
            $this->jobSeekerProfileRepository->insert($jobSeekerProfileDAO);
        }
    }

    private function updateProfile(): void
    {
        $this->jobSeekerProfileDAO = $this->profileHelper->prepareDAO($this->detailsBo);
        $this->jobSeekerProfileRepository->updateByUserId($this->detailsBo->getUserId(), $this->jobSeekerProfileDAO);
    }

    private function recalculateCompleteness(): void
    {
        $profileDetails = $this->fetchProfileWithDetails();
        $completeness = $this->profileHelper->calculateProfileCompleteness($profileDetails->toArray());

        $completenessDAO = new JobSeekerProfileDAO;
        $completenessDAO->setProfileCompleteness($completeness);
        $this->jobSeekerProfileRepository->updateByUserId($this->detailsBo->getUserId(), $completenessDAO);
    }
}
