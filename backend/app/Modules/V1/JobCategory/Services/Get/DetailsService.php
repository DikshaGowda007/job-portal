<?php

namespace App\Modules\V1\JobCategory\Services\Get;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobCategoryRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobCategoryRepository $jobCategoryRepository,
    ) {}

    public function get(int $categoryId): JsonResponse
    {
        try {
            $category = $this->findCategory($categoryId);

            return response()->json(CommonUtils::successDataResponse($this->formatResponse($category)));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function findCategory(int $categoryId)
    {
        $category = $this->jobCategoryRepository->findById($categoryId);
        if ($category->isEmpty()) {
            throw DataNotFoundException::withMessage('Category not found');
        }

        return $category->first();
    }

    private function formatResponse($category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'status' => $category->status,
            'job_count' => $category->jobs()->where('is_deleted', 0)->count(),
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ];
    }
}
