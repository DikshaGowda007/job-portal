<?php

namespace App\Modules\V1\JobCategory\Services\Add;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobCategory\Add\DetailsRequest;
use App\Models\JobCategory;
use App\Modules\V1\JobCategory\Bo\Add\DetailsBo;
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

    public function add(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->ensureSlugAvailable($detailsBo->getSlug());
            $category = $this->insertCategory($detailsBo);

            return response()->json(CommonUtils::successDataResponse($this->formatResponse($category), 'Category created successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_INSERT_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->jobCategoryHelper->prepareAddJobCategoryBo($detailsRequest);
    }

    private function ensureSlugAvailable(string $slug): void
    {
        $slugDetails = $this->jobCategoryRepository->findBySlug($slug);
        if ($slugDetails->isNotEmpty()) {
            throw DataNotFoundException::withMessage('Category with this name already exists');
        }
    }

    private function insertCategory(DetailsBo $detailsBo)
    {
        $jobCategoryDao = $this->jobCategoryHelper->prepareAddJobCategoryDao($detailsBo);

        return $this->jobCategoryRepository->insert($jobCategoryDao);
    }

    private function formatResponse(JobCategory $jobCategory): array
    {
        return [
            'id' => $jobCategory->id,
            'name' => $jobCategory->name,
            'slug' => $jobCategory->slug,
        ];
    }
}
