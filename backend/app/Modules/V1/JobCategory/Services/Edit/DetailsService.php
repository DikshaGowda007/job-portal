<?php

namespace App\Modules\V1\JobCategory\Services\Edit;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobCategory\Edit\DetailsRequest;
use App\Modules\V1\JobCategory\Bo\Edit\DetailsBo;
use App\Modules\V1\JobCategory\Helpers\JobCategoryHelper;
use App\Repositories\V1\JobCategoryRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobCategoryHelper $jobCategoryHelper,
        private JobCategoryRepository $jobCategoryRepository,
    ) {}

    public function edit(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->findCategory($detailsBo->getId());
            $this->ensureSlugAvailable($detailsBo->getSlug(), $detailsBo->getId());
            $this->updateCategory($detailsBo);

            return response()->json(CommonUtils::successResponse('Category updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        return $this->jobCategoryHelper->prepareEditJobCategoryBo($request);
    }

    private function findCategory(int $id): void
    {
        if ($this->jobCategoryRepository->findById($id)->isEmpty()) {
            throw DataNotFoundException::withMessage('Category not found');
        }
    }

    private function ensureSlugAvailable(string $slug, int $excludeId): void
    {
        $existing = $this->jobCategoryRepository->findBySlug($slug);
        if ($existing->isNotEmpty() && $existing->first()->id !== $excludeId) {
            throw DataNotFoundException::withMessage('Category with this name already exists');
        }
    }

    private function updateCategory(DetailsBo $detailsBo): void
    {
        $jobCategoryDao = $this->jobCategoryHelper->prepareEditJobCategoryDao($detailsBo);
        $this->jobCategoryRepository->updateById($detailsBo->getId(), $jobCategoryDao);
    }
}
