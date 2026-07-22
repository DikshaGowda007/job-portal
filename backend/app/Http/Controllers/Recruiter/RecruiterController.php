<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Recruiter\CandidateSearch\DetailsRequest as CandidateSearchRequest;
use App\Http\Requests\V1\Recruiter\MyApplications\DetailsRequest as MyApplicationsRequest;
use App\Http\Requests\V1\Recruiter\MyJobs\DetailsRequest as MyJobsRequest;
use App\Modules\V1\Recruiter\Services\CandidateSearch\DetailsService as CandidateSearchService;
use App\Modules\V1\Recruiter\Services\Dashboard\DetailsService as DashboardService;
use App\Modules\V1\Recruiter\Services\MyApplications\DetailsService as MyApplicationsService;
use App\Modules\V1\Recruiter\Services\MyJobs\DetailsService as MyJobsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class RecruiterController extends Controller
{
    public function dashboard(): JsonResponse
    {
        try {
            $recruiterDashboardService = app(DashboardService::class);

            return $recruiterDashboardService->dashboard();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function myJobs(MyJobsRequest $myJobsRequest): JsonResponse
    {
        try {
            $myJobsService = app(MyJobsService::class);
            $myJobsDetailsBo = $myJobsService->prepareBo($myJobsRequest);

            return $myJobsService->myJobs($myJobsDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function myApplications(MyApplicationsRequest $myApplicationsRequest): JsonResponse
    {
        try {
            $myApplicationsService = app(MyApplicationsService::class);
            $myApplicationsDetailsBo = $myApplicationsService->prepareBo($myApplicationsRequest);

            return $myApplicationsService->myApplications($myApplicationsDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function candidateSearch(CandidateSearchRequest $candidateSearchRequest): JsonResponse
    {
        try {
            $candidateSearchService = app(CandidateSearchService::class);
            $candidateSearchDetailsBo = $candidateSearchService->prepareBo($candidateSearchRequest);

            return $candidateSearchService->search($candidateSearchDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
