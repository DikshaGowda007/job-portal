<?php

namespace App\Modules\V1\Job\Services\Publish;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\Job\Publish\DetailsRequest;
use App\Modules\V1\Job\Bo\Publish\DetailsBo;
use App\Modules\V1\Job\Helpers\JobHelper;
use App\Repositories\V1\JobRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobHelper $jobHelper,
        private JobRepository $jobRepository,
    ) {}

    public function publish(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->publishJob($detailsBo);

            return response()->json(CommonUtils::successResponse('Job published successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_INSERT_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->jobHelper->prepareBo($detailsRequest);
    }

    private function publishJob(DetailsBo $detailsBo)
    {
        $dao = $this->jobHelper->prepareDAO($detailsBo);

        return $this->jobRepository->insert($dao);
    }
}
