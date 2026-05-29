<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Experience\Add;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobSeekerProfile\Experience\Add\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Experience\Add\DetailsBo;
use App\Modules\V1\JobSeekerProfile\Helpers\ExperienceHelper;
use App\Repositories\V1\JobSeekerExperienceRepository;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

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

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        return $this->experienceHelper->prepareBo($request);
    }

    public function add(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $profileId = $this->findProfile();
            $this->addExperience($profileId);

            return response()->json(CommonUtils::successResponse('Experience added successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_INSERT_DATA));
        }
    }

    private function findProfile(): int
    {
        $profileDetails = collect($this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId)->first());

        if ($profileDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Profile not found. Please create your profile first.');
        }

        return $profileDetails->get('id');
    }

    private function addExperience(int $id)
    {
        $jobSeekerExperienceDao = $this->experienceHelper->prepareDao($this->detailsBo);
        $jobSeekerExperienceDao->setJobSeekerProfileId($id);

        return $this->jobSeekerExperienceRepository->insert($jobSeekerExperienceDao);
    }
}
