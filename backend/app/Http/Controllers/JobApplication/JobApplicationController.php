<?php

namespace App\Http\Controllers\JobApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest as ApplyDetailsRequest;
use App\Modules\V1\JobApplication\Services\Apply\DetailsService as ApplyDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class JobApplicationController extends Controller
{
    public function apply(ApplyDetailsRequest $request): JsonResponse
    {
        try {
            $applyDetailsService = app(ApplyDetailsService::class);
            $applyDetailsBo = $applyDetailsService->prepareBo($request);

            return $applyDetailsService->apply($applyDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}