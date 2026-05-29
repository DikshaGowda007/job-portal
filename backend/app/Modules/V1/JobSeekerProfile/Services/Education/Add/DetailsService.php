<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Education\Add;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobSeekerProfile\Education\Add\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Education\Add\DetailsBo;
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

    public function add(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $educationId = $this->addEducation();

            return response()->json(CommonUtils::successDataResponse([
                'id' => $educationId,
                'message' => 'Education added successfully',
            ]));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_INSERT_DATA));
        }
    }

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        return $this->educationHelper->prepareBo($request);
    }

    private function findProfile(): Collection
    {
        return $this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId);
    }

    private function addEducation(): int
    {
        $profileDetails = $this->findProfile();

        if ($profileDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Profile not found. Please create your profile first.');
        }

        $jobSeekerEducationDao = $this->educationHelper->prepareDao($this->detailsBo);
        $jobSeekerEducationDao->setJobSeekerProfileId(collect($profileDetails->first())->get('id'));

        return collect($this->jobSeekerEducationRepository->insert($jobSeekerEducationDao)->toArray())->get('id');
    }
}
