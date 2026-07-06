<?php

namespace App\Modules\V1\JobApplication\Services\MarkRead;

use App\Constants\CommonConstant;
use App\Events\MessageRead;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\ApplicationMessageDAO;
use App\Repositories\V1\ApplicationMessageRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
        private ApplicationMessageRepository $applicationMessageRepository,
        private ApplicationMessageDAO $applicationMessageDAO
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function markRead(int $applicationId): JsonResponse
    {
        try {
            $this->validateParticipation($applicationId);

            $unreadMessages = $this->applicationMessageRepository
                ->fetchByApplicationIdAndSenderId($applicationId, $this->loggedInUserId);

            $this->updateReadAt($applicationId, $unreadMessages->pluck('id')->toArray());

            return response()->json(CommonUtils::successResponse('Messages marked as read'));
        } catch (AccessForbiddenException|DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to mark messages as read'));
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

    private function updateReadAt(int $applicationId, $messageIds): void
    {

        if (! empty($messageIds)) {
            $now = Carbon::now()->format('Y-m-d H:i:s');

            $this->applicationMessageDAO->setReadAt($now);

            $this->applicationMessageRepository->updateReadAtByIds($messageIds, $this->applicationMessageDAO);

            event(new MessageRead(
                $applicationId,
                $this->loggedInUserId,
                $messageIds,
                $now
            ));
        }
    }
}
