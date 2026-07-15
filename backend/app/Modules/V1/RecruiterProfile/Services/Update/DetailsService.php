<?php

namespace App\Modules\V1\RecruiterProfile\Services\Update;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\RecruiterProfile\Update\DetailsRequest;
use App\Modules\V1\RecruiterProfile\Bo\Update\DetailsBo;
use App\Modules\V1\RecruiterProfile\Helpers\ProfileHelper;
use App\Repositories\V1\RecruiterProfileRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    public function __construct(
        private ProfileHelper $profileHelper,
        private RecruiterProfileRepository $recruiterProfileRepository,
        private DetailsBo $detailsBo,
    ) {}

    public function update(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $this->ensureProfileExists();
            $this->updateProfile();

            return response()->json(CommonUtils::successResponse('Company profile updated successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->profileHelper->prepareBo($detailsRequest);
    }

    private function findProfile(): Collection
    {
        return collect($this->recruiterProfileRepository->findByUserId($this->detailsBo->getUserId())->first());
    }

    private function ensureProfileExists(): void
    {
        $profileDetails = $this->findProfile();

        if ($profileDetails->isEmpty()) {
            $initialDao = $this->profileHelper->prepareInitialDao($this->detailsBo->getUserId());
            $this->recruiterProfileRepository->insert($initialDao);
        }
    }

    private function updateProfile(): void
    {
        $dao = $this->profileHelper->prepareDao($this->detailsBo);
        $this->recruiterProfileRepository->updateByUserId($this->detailsBo->getUserId(), $dao);
    }
}
