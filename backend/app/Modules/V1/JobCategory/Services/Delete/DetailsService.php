<?php

namespace App\Modules\V1\JobCategory\Services\Delete;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\DAO\V1\JobCategoryDAO;
use App\Repositories\V1\JobCategoryRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    public function __construct(
        private JobCategoryRepository $jobCategoryRepository,
        private JobCategoryDAO $jobCategoryDao,
    ) {}

    public function delete(int $categoryId): JsonResponse
    {
        try {
            $category = $this->findCategory($categoryId);
            $this->ensureNoActiveJobs($category);
            $this->updateCategory($categoryId);

            return response()->json(CommonUtils::successResponse('Category deleted successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_DELETE_DATA));
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

    private function ensureNoActiveJobs($category): void
    {
        $jobCount = $category->jobs()->where('is_deleted', 0)->count();
        if ($jobCount > 0) {
            throw DataNotFoundException::withMessage("Cannot delete category. It has {$jobCount} active job(s) associated with it.");
        }
    }

    private function updateCategory(int $categoryId): void
    {
        $this->jobCategoryDao->setIsDeleted(CommonConstant::IS_DELETED_YES);
        $this->jobCategoryRepository->updateById($categoryId, $this->jobCategoryDao);
    }
}
