<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Modules\V1\Job\Services\Add\DetailsService as AddJobDetailsService;
use App\Http\Requests\V1\Job\Add\DetailsRequest as AddDetailsRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class JobController extends Controller
{
    public function add(AddDetailsRequest $addDetailsRequest): JsonResponse
    {
        try {
            $addJobDetailsService = app(AddJobDetailsService::class);
            $addJobDetailsBo = $addJobDetailsService->prepareBo($addDetailsRequest);
            return $addJobDetailsService->add($addJobDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
