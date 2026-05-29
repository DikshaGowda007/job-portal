<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Experience\Update;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobSeekerProfile\Experience\Update\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Experience\Update\DetailsBo;
use App\Modules\V1\JobSeekerProfile\Helpers\ExperienceHelper;
use App\Repositories\V1\JobSeekerExperienceRepository;
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
        private ExperienceHelper $experienceHelper,
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
        private JobSeekerExperienceRepository $jobSeekerExperienceRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function update(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $this->validateOwnership();
            $this->updateExperience();

            return response()->json(CommonUtils::successResponse('Experience updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        return $this->experienceHelper->prepareBo($request);
    }

    private function findProfile(): Collection
    {
        $profileDetails = collect($this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId)->first());

        if ($profileDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Profile not found');
        }

        return $profileDetails;
    }

    private function findExperience(): Collection
    {
        return collect($this->jobSeekerExperienceRepository->findById($this->detailsBo->getExperienceId())->first());
    }

    private function validateOwnership(): void
    {
        $profileDetails = $this->findProfile();

        $experienceDetails = $this->findExperience();

        if ($experienceDetails->isEmpty() || $experienceDetails->get('job_seeker_profile_id') !== $profileDetails->get('id')) {
            throw DataNotFoundException::withMessage('Experience not found');
        }
    }

    private function updateExperience(): void
    {
        $jobSeekerExperienceDao = $this->experienceHelper->prepareDao($this->detailsBo);
        $this->jobSeekerExperienceRepository->updateById($this->detailsBo->getExperienceId(), $jobSeekerExperienceDao);
    }
}
