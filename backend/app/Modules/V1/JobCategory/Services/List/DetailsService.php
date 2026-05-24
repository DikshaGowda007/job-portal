<?php

namespace App\Modules\V1\JobCategory\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\JobCategoryRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    public function __construct(
        private JobCategoryRepository $jobCategoryRepository,
    ) {}

    public function list(): JsonResponse
    {
        try {
            $categories = $this->findCategories();

            return response()->json(CommonUtils::successDataResponse($this->formatResponse($categories)));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function findCategories(): Collection
    {
        $categories = $this->jobCategoryRepository->fetchWithJobCount();
        if ($categories->isEmpty()) {
            throw DataNotFoundException::withMessage('No categories found');
        }

        return $categories;
    }

    private function formatResponse(Collection $categories): array
    {
        $list = [];

        foreach ($categories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'status' => $category->status,
                'job_count' => $category->jobs_count,
                'created_at' => $category->created_at,
            ];
        }

        return ['list' => $list];
    }
}
