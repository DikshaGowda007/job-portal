<?php

namespace App\Modules\V1\JobApplication\Services\Apply;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobConstants;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest;
use App\Modules\V1\JobApplication\Bo\Apply\DetailsBo;
use App\Modules\V1\JobApplication\Helpers\JobApplicationHelper;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\JobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;
    public function __construct(
        private DetailsBo $detailsBo,
        private JobApplicationHelper $jobApplicationHelper,
        private JobApplicationRepository $jobApplicationRepository,
        private JobRepository $jobRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function apply(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->detailsBo = $detailsBo;

            $this->validateJob();

            $this->applyJob($detailsBo);

            return response()->json(CommonUtils::successResponse('Application submitted successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(ErrorResponseConstant::ERROR_MESSAGE_GENERAL);
        }
    }

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        return $this->jobApplicationHelper->prepareJobApplyBo($request);
    }

    private function validateJob(): void
    {
        $job = collect($this->jobRepository->findById($this->detailsBo->getJobPostId())->first());

        if ($job->isEmpty()) {
            throw DataNotFoundException::withMessage('Job not found');
        }
        if ($job->get('status') !== JobConstants::STATUS_OPEN) {
            throw DataNotFoundException::withMessage('This job is no longer accepting applications');
        }

        $jobApplication = $this->jobApplicationRepository->findByUserIdAndJobPostId($this->loggedInUserId, $this->detailsBo->getJobPostId());
        if ($jobApplication->isNotEmpty()) {
            throw DataNotFoundException::withMessage('You have already applied for this job');
        }
    }

    private function applyJob(DetailsBo $detailsBo)
    {
        $dao = $this->jobApplicationHelper->prepareJobApplyDAO($detailsBo);

        return $this->jobApplicationRepository->insert($dao);
    }
}
