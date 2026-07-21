<?php

namespace App\Modules\V1\Job\Services\Suggestions;

use App\Repositories\V1\JobRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    private const SUGGESTION_LIMIT = 8;

    public function __construct(
        private JobRepository $jobRepository
    ) {}

    public function suggest(string $type, ?string $query): JsonResponse
    {
        try {
            $suggestions = $type === 'location'
                ? $this->jobRepository->fetchLocationSuggestions($query, self::SUGGESTION_LIMIT)
                : $this->jobRepository->fetchTitleSuggestions($query, self::SUGGESTION_LIMIT);

            return response()->json(CommonUtils::successDataResponse([
                'suggestions' => $suggestions->values()->toArray(),
            ]));
        } catch (\Throwable $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        }
    }
}
