<?php

namespace App\Http\Controllers\JobApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest as ApplyDetailsRequest;
use App\Http\Requests\V1\JobApplication\Get\DetailsRequest as GetDetailsRequest;
use App\Http\Requests\V1\JobApplication\MyApplications\DetailsRequest as MyApplicationsDetailsRequest;
use App\Http\Requests\V1\JobApplication\UpdateStatus\DetailsRequest as UpdateStatusDetailsRequest;
use App\Http\Requests\V1\JobApplication\Withdraw\DetailsRequest as WithdrawDetailsRequest;
use App\Http\Requests\V1\JobApplication\View\DetailsRequest as ViewDetailsRequest;
use App\Modules\V1\JobApplication\Services\Apply\DetailsService as ApplyDetailsService;
use App\Modules\V1\JobApplication\Services\Get\DetailsService as GetDetailsService;
use App\Modules\V1\JobApplication\Services\List\DetailsService as ListDetailsService;
use App\Modules\V1\JobApplication\Services\UpdateStatus\DetailsService as UpdateStatusDetailsService;
use App\Modules\V1\JobApplication\Services\Withdraw\DetailsService as WithdrawDetailsService;
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

    public function myApplications(MyApplicationsDetailsRequest $myApplicationsDetailsRequest): JsonResponse
    {
        try {
            $listDetailsService = app(ListDetailsService::class);
            $jobPostId = $myApplicationsDetailsRequest->input('job_post_id');
            $status = $myApplicationsDetailsRequest->input('status');
            $page = $myApplicationsDetailsRequest->input('page', 1);
            $perPage = $myApplicationsDetailsRequest->input('per_page', 20);

            return $listDetailsService->list($jobPostId, $status, $page, $perPage);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function get(GetDetailsRequest $getDetailsRequest): JsonResponse
    {
        try {
            $getDetailsService = app(GetDetailsService::class);
            $applicationId = $getDetailsRequest->input('application_id');

            return $getDetailsService->get($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function withdraw(WithdrawDetailsRequest $request): JsonResponse
    {
        try {
            $withdrawDetailsService = app(WithdrawDetailsService::class);
            $applicationId = $request->input('application_id');

            return $withdrawDetailsService->withdraw($applicationId);
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

    public function updateStatus(UpdateStatusDetailsRequest $updateStatusDetailsRequest): JsonResponse
    {
        try {
            $updateStatusDetailsService = app(UpdateStatusDetailsService::class);
            $updateStatusDetailsBo = $updateStatusDetailsService->prepareBo($updateStatusDetailsRequest);

            return $updateStatusDetailsService->updateStatus($updateStatusDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
