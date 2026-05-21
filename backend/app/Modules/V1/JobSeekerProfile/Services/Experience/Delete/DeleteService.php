<?php

namespace App\Modules\V1\JobSeekerProfile\Services\Experience\Delete;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\JobSeekerExperienceDAO;
use App\Repositories\V1\JobSeekerExperienceRepository;
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
        private JobSeekerExperienceRepository $jobSeekerExperienceRepository,
        private JobSeekerExperienceDAO $jobSeekerExperienceDAO,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function delete(int $experienceId): JsonResponse
    {
        try {

            $profileId = $this->fetchProfile();
            $experienceDetails = $this->fetchExperience($experienceId);
            $this->validateOwnership($experienceDetails, $profileId);
            $this->deleteExperience($experienceDetails->get('id'));

            return response()->json(CommonUtils::successResponse('Experience deleted successfully'));
        } catch (DataNotFoundException $e) {

            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_DELETE_DATA));
        }
    }

    private function fetchProfile(): int
    {
        $profileDetails = collect($this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId)->first());

        if ($profileDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Profile not found');
        }

        return $profileDetails->get('id');
    }

    private function fetchExperience(int $experienceId): Collection
    {
        $experienceDetails = collect($this->jobSeekerExperienceRepository->findById($experienceId)->first());

        return $experienceDetails->isNotEmpty() ? $experienceDetails : collect();
    }

    private function validateOwnership(Collection $experienceDetails, int $profileId): void
    {
        if ($experienceDetails->isEmpty() || $experienceDetails->get('job_seeker_profile_id') !== $profileId) {
            throw DataNotFoundException::withMessage('Experience not found');
        }
    }

    private function deleteExperience(int $experienceId): void
    {
        $this->jobSeekerExperienceDAO->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->jobSeekerExperienceRepository->updateById($experienceId, $this->jobSeekerExperienceDAO);
    }
}
