<?php

namespace App\Modules\V1\JobSeekerProfile\Services\UploadResume;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\JobSeekerProfile\UploadResume\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\UploadResume\DetailsBo;
use App\Modules\V1\JobSeekerProfile\Helpers\ProfileHelper;
use App\Modules\V1\JobSeekerProfile\Helpers\UploadResumeHelper;
use App\Repositories\DAO\V1\JobSeekerProfileDAO;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class DetailsService
{
    use AccessRightsTrait;

    private DetailsBo $detailsBo;

    private Collection $profileDetails;

    public function __construct(
        private JobSeekerProfileRepository $jobSeekerProfileRepository,
        private ProfileHelper $profileHelper,
        private JobSeekerProfileDAO $jobSeekerProfileDao,
        private UploadResumeHelper $uploadResumeHelper,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function upload(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;

        try {
            $this->findProfile();
            $this->deleteOldResume();
            $filePath = $this->storeResumeFile();
            $this->updateProfileWithResume($filePath);

            return response()->json(CommonUtils::successResponse('Resume uploaded successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->uploadResumeHelper->prepareBo($detailsRequest);
    }

    private function findProfile(): void
    {
        $this->profileDetails = collect($this->jobSeekerProfileRepository->findByUserId($this->loggedInUserId)->first());

        if ($this->profileDetails->isEmpty()) {
            $this->createProfile();
        }
    }

    private function createProfile(): void
    {
        $jobSeekerProfileDao = $this->profileHelper->prepareJobSeekerProfileDao($this->loggedInUserId);
        $jobSeekerProfile = $this->jobSeekerProfileRepository->insert($jobSeekerProfileDao);
        $this->profileDetails = collect($jobSeekerProfile->toArray());
    }

    private function deleteOldResume(): void
    {
        $resumePath = $this->profileDetails->get('resume_path');

        if ($resumePath && Storage::disk('public')->exists($resumePath)) {
            Storage::disk('public')->delete($resumePath);
        }
    }

    private function storeResumeFile(): string
    {
        $resumeFile = $this->detailsBo->getUploadedFile();
        $resumeFileName = time().'_'.$resumeFile->getClientOriginalName();

        return $resumeFile->storeAs("resumes/{$this->loggedInUserId}", $resumeFileName, 'public');
    }

    private function updateProfileWithResume(string $filePath): void
    {
        $resumeFile = $this->detailsBo->getUploadedFile();
        $profileData = $this->profileDetails->toArray();
        $profileData['resume_path'] = $filePath;

        $this->jobSeekerProfileDao->setResumePath($filePath);
        $this->jobSeekerProfileDao->setResumeFilename($resumeFile->getClientOriginalName());
        $this->jobSeekerProfileDao->setResumeUploadedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $this->jobSeekerProfileDao->setProfileCompleteness($this->profileHelper->calculateProfileCompleteness($profileData));

        $this->jobSeekerProfileRepository->updateByUserId($this->loggedInUserId, $this->jobSeekerProfileDao);
    }
}
