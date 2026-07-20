<?php

namespace App\Modules\V1\JobApplication\Services\History;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\UserConstant;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobApplicationHistoryRepository;
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
        private JobApplicationHistoryRepository $jobApplicationHistoryRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function getHistory(int $applicationId): JsonResponse
    {
        try {
            $application = $this->findApplication($applicationId);
            $this->hasAccess($application);
            $histories = $this->jobApplicationHistoryRepository->fetchByApplicationId($applicationId);
            $response = $this->formatResponse($applicationId, $application, $histories);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (DataNotFoundException|AccessForbiddenException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function findApplication(int $applicationId): Collection
    {
        $application = collect($this->jobApplicationRepository->findByIdWithJobPostAndUser($applicationId)->first());

        if ($application->isEmpty()) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        return $application;
    }

    private function hasAccess(Collection $application): void
    {
        $isAdmin = in_array($this->loggedInUserRole, [UserConstant::USER_ROLE_ADMIN, UserConstant::USER_ROLE_SUB_ADMIN]);
        $isOwningRecruiter = $this->loggedInUserRole == UserConstant::USER_ROLE_RECRUITER && $this->loggedInUserId === $application->get('job_post')['user_id'];
        $isOwningSeeker = $this->loggedInUserRole == UserConstant::USER_ROLE_JOB_SEEKER && $this->loggedInUserId === ($application->get('user')['id'] ?? null);

        if (! $isAdmin && ! $isOwningRecruiter && ! $isOwningSeeker) {
            throw AccessForbiddenException::withMessage('Unauthorized to view this application history');
        }
    }

    private function formatResponse(int $applicationId, Collection $application, $histories): array
    {
        return [
            'application_id' => $applicationId,
            'current_status' => $application->get('status'),
            'timeline' => $this->formatTimeline($histories),
        ];
    }

    private function formatTimeline($histories): array
    {
        $timeline = [];

        foreach ($histories as $history) {
            $timeline[] = [
                'id' => $history->id,
                'previous_status' => $history->previous_status,
                'new_status' => $history->new_status,
                'changed_by' => $history->changedByUser ? [
                    'id' => $history->changedByUser->id,
                    'name' => $history->changedByUser->name,
                ] : null,
                'notes' => $history->notes,
                'interview_scheduled_at' => $history->interview_scheduled_at,
                'interview_location' => $history->interview_location,
                'created_at' => $history->created_at,
            ];
        }

        return $timeline;
    }
}
