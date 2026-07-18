<?php

namespace App\Modules\V1\Job\Services\Publish;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobConstants;
use App\Http\Requests\V1\Job\Publish\DetailsRequest;
use App\Modules\V1\Job\Bo\Publish\DetailsBo;
use App\Modules\V1\Job\Helpers\JobHelper;
use App\Modules\V1\JobAlert\Services\Match\DetailsService as JobAlertMatchService;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Repositories\V1\JobRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobHelper $jobHelper,
        private JobRepository $jobRepository,
        private JobPostDAO $jobPostDao,
        private JobAlertMatchService $jobAlertMatchService,
    ) {}

    public function publish(DetailsBo $detailsBo): JsonResponse
    {
        try {
            if ($detailsBo->getId()) {
                $this->publishExisting($detailsBo->getId());
            } else {
                $this->publishNew($detailsBo);
            }

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

    private function publishExisting(int $id): void
    {
        $this->jobPostDao->setStatus(JobConstants::STATUS_OPEN);
        $this->jobPostDao->setModifiedByUserId(auth()->id());
        $this->jobRepository->updateById($id, $this->jobPostDao);

        $job = $this->jobRepository->findById($id)->first();
        $this->jobAlertMatchService->notifyMatchingAlerts($job);
    }

    private function publishNew(DetailsBo $detailsBo): void
    {
        $dao = $this->jobHelper->prepareDao($detailsBo);
        $dao->setStatus(JobConstants::STATUS_OPEN);
        $job = $this->jobRepository->insert($dao);

        $this->jobAlertMatchService->notifyMatchingAlerts($job);
    }
}
