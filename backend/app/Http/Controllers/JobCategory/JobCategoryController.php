<?php

namespace App\Http\Controllers\JobCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobCategory\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\JobCategory\Delete\DetailsRequest as DeleteDetailsRequest;
use App\Http\Requests\V1\JobCategory\Edit\DetailsRequest as EditDetailsRequest;
use App\Http\Requests\V1\JobCategory\Get\DetailsRequest as GetDetailsRequest;
use App\Http\Requests\V1\JobCategory\List\DetailsRequest as ListDetailsRequest;
use App\Modules\V1\JobCategory\Services\Add\DetailsService as AddDetailsService;
use App\Modules\V1\JobCategory\Services\Delete\DetailsService as DeleteDetailsService;
use App\Modules\V1\JobCategory\Services\Edit\DetailsService as EditDetailsService;
use App\Modules\V1\JobCategory\Services\Get\DetailsService as GetDetailsService;
use App\Modules\V1\JobCategory\Services\List\DetailsService as ListDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class JobCategoryController extends Controller
{
    public function add(AddDetailsRequest $addDetailsRequest): JsonResponse
    {
        try {
            $addDetailsService = app(AddDetailsService::class);
            $addDetailsBo = $addDetailsService->prepareBo($addDetailsRequest);

            return $addDetailsService->add($addDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function edit(EditDetailsRequest $editDetailsRequest): JsonResponse
    {
        try {
            $editDetailsService = app(EditDetailsService::class);
            $editDetailsBo = $editDetailsService->prepareBo($editDetailsRequest);

            return $editDetailsService->edit($editDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function delete(DeleteDetailsRequest $deleteDetailsRequest): JsonResponse
    {
        try {
            $deleteDetailsService = app(DeleteDetailsService::class);
            $categoryId = $deleteDetailsRequest->input('id');

            return $deleteDetailsService->delete($categoryId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function list(ListDetailsRequest $listDetailsRequest): JsonResponse
    {
        try {
            $listDetailsService = app(ListDetailsService::class);

            return $listDetailsService->list();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function get(GetDetailsRequest $getDetailsRequest): JsonResponse
    {
        try {
            $getDetailsService = app(GetDetailsService::class);
            $categoryId = $getDetailsRequest->input('id');

            return $getDetailsService->get($categoryId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
