<?php

namespace App\Modules\V1\JobApplication\Services\SendMessage;

use App\Constants\CommonConstant;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\ApplicationMessageDAO;
use App\Repositories\V1\ApplicationMessageRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
        private ApplicationMessageRepository $applicationMessageRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function send(int $applicationId, string $message): JsonResponse
    {
        try {
            $this->validateOwnership($applicationId);

            $savedMessage = $this->updateApplicationMessage($applicationId, $message);

            $response = $this->formatResponse(collect($savedMessage));

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to send message'));
        }
    }

    private function validateOwnership(int $applicationId): void
    {
        $application = $this->jobApplicationRepository->findByIdWithJobPostAndUser($applicationId)->first();

        if (! $application) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        if ($application->user_id !== $this->loggedInUserId) {
            throw AccessForbiddenException::withMessage();
        }
    }

    private function updateApplicationMessage(int $applicationId, string $message)
    {
        $applicationMessageDAO = new ApplicationMessageDAO;
        $applicationMessageDAO->setApplicationId($applicationId);
        $applicationMessageDAO->setSenderId($this->loggedInUserId);
        $applicationMessageDAO->setMessage($message);

        return $this->applicationMessageRepository->insert($applicationMessageDAO);
    }

    private function formatResponse(Collection $savedMessage): array
    {
        return [
            'id' => $savedMessage->get('id'),
            'message' => $savedMessage->get('message'),
            'sender_id' => $savedMessage->get('sender_id'),
            'created_at' => $savedMessage->get('created_at'),
        ];
    }
}
