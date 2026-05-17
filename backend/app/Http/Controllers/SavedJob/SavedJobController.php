<?php

namespace App\Http\Controllers\SavedJob;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SavedJob\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\SavedJob\Delete\DetailsRequest as DeleteDetailsRequest;
use App\Http\Requests\V1\SavedJob\List\DetailsRequest as ListDetailsRequest;
use App\Modules\V1\SavedJob\Services\Add\DetailsService as AddDetailsService;
use App\Modules\V1\SavedJob\Services\Delete\DetailsService as DeleteDetailsService;
use App\Modules\V1\SavedJob\Services\List\DetailsService as ListDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class SavedJobController extends Controller
{
    public function add(AddDetailsRequest $addDetailsRequest): JsonResponse
    {
        try {
            $jobPostId = $addDetailsRequest->input('job_post_id');
            $addDetailsService = app(AddDetailsService::class);

            return $addDetailsService->add($jobPostId);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 200);
        }
    }

    public function list(ListDetailsRequest $listDetailsRequest): JsonResponse
    {
        try {
            $listDetailsService = app(ListDetailsService::class);

            return $listDetailsService->list();
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 200);
        }
    }

    public function delete(DeleteDetailsRequest $deleteDetailsRequest): JsonResponse
    {
        try {

            $jobPostId = $deleteDetailsRequest->input('job_post_id');
            $deleteDetailsService = app(DeleteDetailsService::class);

            return $deleteDetailsService->delete($jobPostId);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 200);
        }
    }
}
