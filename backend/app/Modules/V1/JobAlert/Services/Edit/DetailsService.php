<?php

namespace App\Modules\V1\JobAlert\Services\Edit;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobAlert\Edit\DetailsRequest;
use App\Modules\V1\JobAlert\Bo\Edit\DetailsBo;
use App\Modules\V1\JobAlert\Helpers\JobAlertHelper;
use App\Repositories\V1\JobAlertRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    private DetailsBo $detailsBo;

    public function __construct(
        private JobAlertHelper $jobAlertHelper,
        private JobAlertRepository $jobAlertRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function edit(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;
        try {
            $this->findOwnedAlert();
            $this->updateAlert();

            return response()->json(CommonUtils::successResponse('Job alert updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->jobAlertHelper->prepareBo($detailsRequest);
    }

    private function findOwnedAlert(): void
    {
        $alert = $this->jobAlertRepository->findByIdAndUserId($this->detailsBo->getId(), $this->loggedInUserId);
        if (! $alert) {
            throw DataNotFoundException::withMessage('Job alert not found');
        }
    }

    private function updateAlert(): void
    {
        $dao = $this->jobAlertHelper->prepareDao($this->detailsBo);
        $this->jobAlertRepository->updateById($this->detailsBo->getId(), $dao);
    }
}
