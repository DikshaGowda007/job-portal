<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Education\Update;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobSeekerProfile\Education\Update\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Education\Update\DetailsBo;
use App\Modules\V1\JobSeekerProfile\Helpers\EducationHelper;
use App\Repositories\V1\JobSeekerEducationRepository;
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
        private EducationHelper $educationHelper,
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
        private JobSeekerEducationRepository $jobSeekerEducationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(DetailsRequest $updateEducationDetailsRequest): DetailsBo
    {
        return $this->educationHelper->prepareBo($updateEducationDetailsRequest);
    }

    public function update(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $this->validateOwnership();
            $this->updateEducation();

            return response()->json(CommonUtils::successResponse('Education updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    private function fetchProfile(): Collection
    {
        return $this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId);
    }

    private function fetchEducation(): Collection
    {
        $educationModel = $this->jobSeekerEducationRepository->findById($this->detailsBo->getEducationId());

        return $educationModel ? collect($educationModel->toArray()) : collect();
    }

    private function validateOwnership(): void
    {
        $profileDetails = $this->fetchProfile();

        if ($profileDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Profile not found');
        }

        $educationDetails = $this->fetchEducation();

        if ($educationDetails->isEmpty() || $educationDetails->get('job_seeker_profile_id') !== $profileDetails->first()->id) {
            throw DataNotFoundException::withMessage('Education not found');
        }
    }

    private function updateEducation(): void
    {
        $jobSeekerEducationDAO = $this->educationHelper->prepareDAO($this->detailsBo);
        $this->jobSeekerEducationRepository->updateById($this->detailsBo->getEducationId(), $jobSeekerEducationDAO);
    }
}
