<?php

namespace App\Modules\V1\Job\Services\Add;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\Job\Publish\DetailsRequest;
use App\Modules\V1\Job\Bo\Add\DetailsBo;
use App\Modules\V1\Job\Helpers\JobHelper;
use App\Repositories\V1\JobRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobHelper $jobHelper,
        private DetailsBo $detailsBo,
        private JobRepository $jobRepository,
    ) {}

    public function add(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->addJobs($detailsBo);

            return response()->json(CommonUtils::successResponse('Job added successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_INSERT_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->jobHelper->prepareBo($detailsRequest);
    }

    private function addJobs(DetailsBo $detailsBo)
    {
        $dao = $this->jobHelper->prepareDao($detailsBo);

        return $this->jobRepository->insert($dao);
    }
}
