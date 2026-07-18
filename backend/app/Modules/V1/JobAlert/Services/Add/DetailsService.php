<?php

namespace App\Modules\V1\JobAlert\Services\Add;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\JobAlert\Add\DetailsRequest;
use App\Modules\V1\JobAlert\Bo\Add\DetailsBo;
use App\Modules\V1\JobAlert\Helpers\JobAlertHelper;
use App\Repositories\V1\JobAlertRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobAlertHelper $jobAlertHelper,
        private JobAlertRepository $jobAlertRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function add(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->saveAlert($detailsBo);

            return response()->json(CommonUtils::successResponse('Job alert created successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_INSERT_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->jobAlertHelper->prepareBo($detailsRequest);
    }

    private function saveAlert(DetailsBo $detailsBo): void
    {
        $dao = $this->jobAlertHelper->prepareDao($detailsBo);
        $this->jobAlertRepository->insert($dao);
    }
}
