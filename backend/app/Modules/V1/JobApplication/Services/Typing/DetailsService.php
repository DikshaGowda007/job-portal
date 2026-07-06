<?php

namespace App\Modules\V1\JobApplication\Services\Typing;

use App\Constants\CommonConstant;
use App\Events\UserTyping;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function typing(int $applicationId, bool $isTyping): JsonResponse
    {
        try {
            $this->validateParticipation($applicationId);

            // UserTyping implements ShouldBroadcastNow — fires immediately, no queue
            event(new UserTyping(
                $applicationId,
                $this->loggedInUserId,
                trim($this->loggedInUserFirstName.' '.$this->loggedInUserLastName),
                $isTyping
            ));

            return response()->json(CommonUtils::successResponse('Typing status broadcast'));
        } catch (AccessForbiddenException|DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            dd($e);
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to broadcast typing status'));
        }
    }

    private function validateParticipation(int $applicationId): void
    {
        $application = $this->jobApplicationRepository->findByIdWithJobPostAndUser($applicationId)->first();

        if (! $application) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        $isSeeker = $application->user_id === $this->loggedInUserId;
        $isRecruiter = $application->jobPost->user_id === $this->loggedInUserId;

        if (! $isSeeker && ! $isRecruiter) {
            throw AccessForbiddenException::withMessage();
        }
    }
}
