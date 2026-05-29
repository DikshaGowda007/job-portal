<?php

namespace App\Modules\V1\Job\Services\Edit;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\Job\Edit\DetailsRequest;
use App\Modules\V1\Job\Bo\Edit\DetailsBo;
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

    public function edit(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;
        try {
            $this->findJob();
            $this->updateJob();

            return response()->json(CommonUtils::successResponse('Job updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->jobHelper->prepareBo($detailsRequest);
    }

    private function findJob(): void
    {
        $jobDetails = collect($this->jobRepository->findById($this->detailsBo->getId())->first());
        if ($jobDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Job not found');
        }
    }

    private function updateJob()
    {
        $dao = $this->jobHelper->prepareDao($this->detailsBo);

        return $this->jobRepository->updateById($this->detailsBo->getId(), $dao);
    }
}
