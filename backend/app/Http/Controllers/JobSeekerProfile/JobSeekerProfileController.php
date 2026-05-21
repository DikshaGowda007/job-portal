<?php

namespace App\Http\Controllers\JobSeekerProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobSeekerProfile\Education\Add\DetailsRequest as AddEducationDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Education\Delete\DetailsRequest as DeleteEducationDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Education\Update\DetailsRequest as UpdateEducationDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Experience\Add\DetailsRequest as AddExperienceDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Experience\Delete\DetailsRequest as DeleteExperienceDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Experience\Update\DetailsRequest as UpdateExperienceDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Update\DetailsRequest as UpdateDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\View\DetailsRequest as ViewDetailsRequest;
use App\Modules\V1\JobSeekerProfile\Services\Education\Add\DetailsService as AddEducationDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Education\Delete\DeleteService as DeleteEducationDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Education\Update\DetailsService as UpdateEducationDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Experience\Add\DetailsService as AddExperienceDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Experience\Delete\DeleteService as DeleteExperienceDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Experience\Update\DetailsService as UpdateExperienceDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Get\DetailsService as GetDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\Update\DetailsService as UpdateDetailsService;
use App\Modules\V1\JobSeekerProfile\Services\View\DetailsService as ViewDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class JobSeekerProfileController extends Controller
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

    public function view(ViewDetailsRequest $viewDetailsRequest): JsonResponse
    {
        try {
            $viewDetailsService = app(ViewDetailsService::class);

            return $viewDetailsService->view($viewDetailsRequest);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }


    public function addExperience(AddExperienceDetailsRequest $addExperienceDetailsRequest): JsonResponse
    {
        try {
            $addExperienceDetailsService = app(AddExperienceDetailsService::class);
            $addExperienceDetailsBo = $addExperienceDetailsService->prepareBo($addExperienceDetailsRequest);

            return $addExperienceDetailsService->add($addExperienceDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function updateExperience(UpdateExperienceDetailsRequest $updateExperienceDetailsRequest): JsonResponse
    {
        try {
            $updateExperienceDetailsService = app(UpdateExperienceDetailsService::class);
            $updateExperienceDetailsBo = $updateExperienceDetailsService->prepareBo($updateExperienceDetailsRequest);

            return $updateExperienceDetailsService->update($updateExperienceDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function deleteExperience(DeleteExperienceDetailsRequest $deleteExperienceDetailsRequest): JsonResponse
    {
        try {
            $deleteExperienceDetailsService = app(DeleteExperienceDetailsService::class);
            $experienceId = $deleteExperienceDetailsRequest->input('experience_id');

            return $deleteExperienceDetailsService->delete($experienceId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function addEducation(AddEducationDetailsRequest $addEducationDetailsRequest): JsonResponse
    {
        try {
            $addEducationDetailsService = app(AddEducationDetailsService::class);
            $addEducationDetailsBo = $addEducationDetailsService->prepareBo($addEducationDetailsRequest);

            return $addEducationDetailsService->add($addEducationDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function updateEducation(UpdateEducationDetailsRequest $updateEducationDetailsRequest): JsonResponse
    {
        try {
            $updateEducationDetailsService = app(UpdateEducationDetailsService::class);
            $updateEducationDetailsBo = $updateEducationDetailsService->prepareBo($updateEducationDetailsRequest);

            return $updateEducationDetailsService->update($updateEducationDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function deleteEducation(DeleteEducationDetailsRequest $deleteEducationDetailsRequest): JsonResponse
    {
        try {
            $deleteEducationDetailsService = app(DeleteEducationDetailsService::class);
            $educationId = $deleteEducationDetailsRequest->input('education_id');

            return $deleteEducationDetailsService->delete($educationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
