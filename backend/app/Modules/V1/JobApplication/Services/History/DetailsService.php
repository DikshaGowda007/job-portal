<?php

namespace App\Modules\V1\JobApplication\Services\History;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
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
        private JobApplicationRepository $applicationRepository,
        private JobApplicationHistoryRepository $historyRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function getHistory(int $applicationId): JsonResponse
    {
        try {
            $application = $this->findApplication($applicationId);
            $histories = $this->historyRepository->fetchByApplicationId($applicationId);
            $response = $this->formatResponse($applicationId, $application, $histories);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function findApplication(int $applicationId): Collection
    {
        $application = collect($this->applicationRepository->findByIdWithJobPostAndUser($applicationId)->first());

        if ($application->isEmpty()) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        return $application;
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
                'created_at' => $history->created_at,
            ];
        }

        return $timeline;
    }
}
