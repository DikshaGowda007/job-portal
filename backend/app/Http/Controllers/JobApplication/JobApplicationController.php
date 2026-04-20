<?php

namespace App\Http\Controllers\JobApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest as ApplyDetailsRequest;
use App\Http\Requests\V1\JobApplication\MyApplications\DetailsRequest as MyApplicationsDetailsRequest;
use App\Http\Requests\V1\JobApplication\View\DetailsRequest as ViewDetailsRequest;
use App\Modules\V1\JobApplication\Services\Apply\DetailsService as ApplyDetailsService;
use App\Modules\V1\JobApplication\Services\List\DetailsService as ListDetailsService;
use App\Modules\V1\JobApplication\Services\View\DetailsService as ViewDetailsService;
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

    public function myApplications(MyApplicationsDetailsRequest $request): JsonResponse
    {
        try {
            $service = app(ListDetailsService::class);
            $userId = $request->input('user_id');
            $status = $request->input('status');

            return $service->list($userId, $status);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function view(ViewDetailsRequest $viewDetailsRequest): JsonResponse
    {
        try {
            $viewDetailsService = app(ViewDetailsService::class);
            $applicationId = $viewDetailsRequest->input('application_id');

            return $viewDetailsService->view($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
