<?php

namespace App\Http\Controllers\RecruiterProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RecruiterProfile\Update\DetailsRequest as UpdateDetailsRequest;
use App\Modules\V1\RecruiterProfile\Services\Get\DetailsService as GetDetailsService;
use App\Modules\V1\RecruiterProfile\Services\Update\DetailsService as UpdateDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class RecruiterProfileController extends Controller
{
    public function get(): JsonResponse
    {
        try {
            $getDetailsService = app(GetDetailsService::class);

            return $getDetailsService->get();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function update(UpdateDetailsRequest $updateDetailsRequest): JsonResponse
    {
        try {
            $updateDetailsService = app(UpdateDetailsService::class);
            $updateDetailsBo = $updateDetailsService->prepareBo($updateDetailsRequest);

            return $updateDetailsService->update($updateDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
