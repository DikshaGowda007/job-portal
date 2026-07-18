<?php

namespace App\Modules\V1\JobAlert\Services\Delete;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Models\JobAlert;
use App\Repositories\DAO\V1\JobAlertDAO;
use App\Repositories\V1\JobAlertRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobAlertRepository $jobAlertRepository,
        private JobAlertDAO $jobAlertDao,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $alert = $this->findOwnedAlert($id);
            $this->deleteAlert($alert->id);

            return response()->json(CommonUtils::successResponse('Job alert removed successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to remove job alert'));
        }
    }

    private function findOwnedAlert(int $id): JobAlert
    {
        $alert = $this->jobAlertRepository->findByIdAndUserId($id, $this->loggedInUserId);
        if (! $alert) {
            throw DataNotFoundException::withMessage('Job alert not found');
        }

        return $alert;
    }

    private function deleteAlert(int $id): void
    {
        $this->jobAlertDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->jobAlertRepository->updateById($id, $this->jobAlertDao);
    }
}
