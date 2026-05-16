<?php

namespace App\Modules\V1\JobApplication\Services\Apply;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobApplicationConstants;
use App\Constants\JobConstants;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest;
use App\Mail\ApplicationSubmittedMail;
use App\Models\User;
use App\Modules\V1\JobApplication\Bo\Apply\DetailsBo;
use App\Modules\V1\JobApplication\Helpers\JobApplicationHelper;
use App\Repositories\DAO\V1\JobApplicationHistoryDAO;
use App\Repositories\V1\JobApplicationHistoryRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\JobRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private DetailsBo $detailsBo,
        private JobApplicationHelper $jobApplicationHelper,
        private JobApplicationRepository $jobApplicationRepository,
        private JobApplicationHistoryRepository $historyRepository,
        private JobRepository $jobRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    private int $recruiterId;

    private string $jobTitle;

    public function apply(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $this->detailsBo = $detailsBo;

            $this->validateJob();

            $application = $this->applyJob($detailsBo);

            $this->logInitialStatus($application->id, $detailsBo->getUserId());
            $this->sendApplicationNotification($detailsBo->getUserId(), $application->id);

            return response()->json(CommonUtils::successResponse('Application submitted successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    public function prepareBo(DetailsRequest $request): DetailsBo
    {
        return $this->jobApplicationHelper->prepareJobApplyBo($request);
    }

    private function validateJob(): void
    {
        $job = collect($this->jobRepository->findById($this->detailsBo->getJobPostId())->first());

        if ($job->isEmpty()) {
            throw DataNotFoundException::withMessage('Job not found');
        }
        if ($job->get('status') !== JobConstants::STATUS_OPEN) {
            throw DataNotFoundException::withMessage('This job is no longer accepting applications');
        }

        $this->recruiterId = $job->get('user_id');
        $this->jobTitle = $job->get('title');

        $jobApplication = $this->jobApplicationRepository->findByUserIdAndJobPostId($this->loggedInUserId, $this->detailsBo->getJobPostId());
        if ($jobApplication->isNotEmpty()) {
            throw DataNotFoundException::withMessage('You have already applied for this job');
        }
    }

    private function applyJob(DetailsBo $detailsBo)
    {
        $dao = $this->jobApplicationHelper->prepareJobApplyDAO($detailsBo);

        return $this->jobApplicationRepository->insert($dao);
    }

    private function sendApplicationNotification(int $applicantId, int $applicationId): void
    {
        try {
            $recruiter = User::find($this->recruiterId);
            $applicant = User::find($applicantId);

            if ($recruiter && $recruiter->email && $applicant) {
                $recruiterName = trim($recruiter->first_name.' '.$recruiter->last_name);
                $candidateName = trim($applicant->first_name.' '.$applicant->last_name);

                Mail::to($recruiter->email)->send(new ApplicationSubmittedMail(
                    $recruiterName,
                    $this->jobTitle,
                    $candidateName,
                    $applicant->email,
                    $applicationId
                ));
            }
        } catch (\Exception $e) {
            CommonUtils::handleException('Email notification failed: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function logInitialStatus(int $applicationId, int $userId): void
    {
        try {
            $historyDao = new JobApplicationHistoryDAO;
            $historyDao->setJobApplicationId($applicationId);
            $historyDao->setPreviousStatus(null);
            $historyDao->setNewStatus(JobApplicationConstants::STATUS_PENDING);
            $historyDao->setChangedBy($userId);
            $historyDao->setNotes('Application submitted');

            $this->historyRepository->insert($historyDao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to log application history: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }
}
