<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Modules\V1\Job\Services\Add\DetailsService as AddJobDetailsService;
use App\Modules\V1\Job\Services\Delete\DeleteService as DeleteJobDetailsService;
use App\Modules\V1\Job\Services\Edit\DetailsService as EditJobDetailsService;
use App\Modules\V1\Job\Services\List\DetailsService as ListJobDetailsService;
use App\Modules\V1\Job\Services\Get\DetailsService as GetJobDetailsService;
use App\Http\Requests\V1\Job\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\Job\Edit\DetailsRequest as EditDetailsRequest;
use App\Http\Requests\V1\Job\List\DetailsRequest as ListDetailsRequest;
use App\Http\Requests\V1\Job\Delete\DetailsRequest as DeleteDetailsRequest;
use App\Http\Requests\V1\Job\Get\DetailsRequest as GetDetailsRequest;
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

    public function edit(EditDetailsRequest $editDetailsRequest): JsonResponse
    {
        try {
            $editJobDetailsService = app(EditJobDetailsService::class);
            $editJobDetailsBo = $editJobDetailsService->prepareBo($editDetailsRequest);
            return $editJobDetailsService->edit($editJobDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function delete(DeleteDetailsRequest $deleteDetailsRequest): JsonResponse
    {
        try {
            $deleteJobDetailsService = app(DeleteJobDetailsService::class);
            $jobId = $deleteDetailsRequest->input('id');
            return $deleteJobDetailsService->delete($jobId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function list(ListDetailsRequest $listDetailsRequest): JsonResponse
    {
        try {
            $listJobDetailsService = app(ListJobDetailsService::class);
            $text = $listDetailsRequest->input('text');
            return $listJobDetailsService->list($text);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function get(GetDetailsRequest $getDetailsRequest): JsonResponse
    {
        try {
            $getJobDetailsService = app(GetJobDetailsService::class);
            $jobId = $getDetailsRequest->input('id');
            return $getJobDetailsService->get($jobId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
