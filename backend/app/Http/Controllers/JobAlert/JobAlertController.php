<?php

namespace App\Http\Controllers\JobAlert;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobAlert\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\JobAlert\Delete\DetailsRequest as DeleteDetailsRequest;
use App\Http\Requests\V1\JobAlert\Edit\DetailsRequest as EditDetailsRequest;
use App\Http\Requests\V1\JobAlert\List\DetailsRequest as ListDetailsRequest;
use App\Modules\V1\JobAlert\Services\Add\DetailsService as AddDetailsService;
use App\Modules\V1\JobAlert\Services\Delete\DetailsService as DeleteDetailsService;
use App\Modules\V1\JobAlert\Services\Edit\DetailsService as EditDetailsService;
use App\Modules\V1\JobAlert\Services\List\DetailsService as ListDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class JobAlertController extends Controller
{
    public function add(AddDetailsRequest $addDetailsRequest): JsonResponse
    {
        try {
            $addDetailsService = app(AddDetailsService::class);
            $addDetailsBo = $addDetailsService->prepareBo($addDetailsRequest);

            return $addDetailsService->add($addDetailsBo);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 200);
        }
    }

    public function edit(EditDetailsRequest $editDetailsRequest): JsonResponse
    {
        try {
            $editDetailsService = app(EditDetailsService::class);
            $editDetailsBo = $editDetailsService->prepareBo($editDetailsRequest);

            return $editDetailsService->edit($editDetailsBo);
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
            $id = $deleteDetailsRequest->input('id');
            $deleteDetailsService = app(DeleteDetailsService::class);

            return $deleteDetailsService->delete($id);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 200);
        }
    }
}
