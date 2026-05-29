<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Education\Delete;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\JobSeekerEducationDAO;
use App\Repositories\V1\JobSeekerEducationRepository;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DeleteService
{
    use AccessRightsTrait;

    public function __construct(
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
        private JobSeekerEducationRepository $jobSeekerEducationRepository,
        private JobSeekerEducationDAO $jobSeekerEducationDao,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function delete(int $educationId): JsonResponse
    {
        try {
            $this->validateOwnership($educationId);
            $this->deleteEducation($educationId);

            return response()->json(CommonUtils::successResponse('Education deleted successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_DELETE_DATA));
        }
    }

    private function findProfile(): Collection
    {
        return $this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId);
    }

    private function findEducation(int $educationId): Collection
    {
        $educationModel = $this->jobSeekerEducationRepository->findById($educationId);

        return $educationModel ? collect($educationModel->toArray()) : collect();
    }

    private function validateOwnership(int $educationId): void
    {
        $profileDetails = $this->findProfile();

        if ($profileDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Profile not found');
        }

        $educationDetails = $this->findEducation($educationId);

        if ($educationDetails->isEmpty() || $educationDetails->get('job_seeker_profile_id') !== $profileDetails->first()->id) {
            throw DataNotFoundException::withMessage('Education not found');
        }
    }

    private function deleteEducation(int $educationId): void
    {
        $this->jobSeekerEducationDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->jobSeekerEducationRepository->updateById($educationId, $this->jobSeekerEducationDao);
    }
}
