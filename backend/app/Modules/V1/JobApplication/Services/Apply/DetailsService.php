<?php

namespace App\Modules\V1\JobApplication\Services\Apply;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobApplicationConstants;
use App\Constants\JobConstants;
use App\Constants\NotificationConstants;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest;
use App\Mail\ApplicationSubmittedMail;
use App\Modules\V1\JobApplication\Bo\Apply\DetailsBo;
use App\Modules\V1\JobApplication\Helpers\JobApplicationHelper;
use App\Repositories\DAO\V1\NotificationDAO;
use App\Repositories\V1\JobApplicationHistoryRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\JobRepository;
use App\Repositories\V1\NotificationRepository;
use App\Repositories\V1\UserRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class DetailsService
{
    use AccessRightsTrait;

    private DetailsBo $detailsBo;

    private int $recruiterId;

    private string $jobTitle;

    public function __construct(
        private JobApplicationHelper $jobApplicationHelper,
        private JobApplicationRepository $jobApplicationRepository,
        private JobApplicationHistoryRepository $historyRepository,
        private JobRepository $jobRepository,
        private UserRepository $userRepository,
        private NotificationRepository $notificationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function apply(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;
        try {
            $this->validateJob();

            $application = $this->applyJob();
            $this->logInitialStatus($application->id);
            $this->notifyRecruiter($application->id);
            $this->createRecruiterNotification($application->id);

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

        $jobApplication = collect($this->jobApplicationRepository->findByUserIdAndJobPostId($this->loggedInUserId, $this->detailsBo->getJobPostId())->first());
        if ($jobApplication->isNotEmpty()) {
            throw DataNotFoundException::withMessage('You have already applied for this job');
        }
    }

    private function applyJob()
    {
        $dao = $this->jobApplicationHelper->prepareJobApplyDao($this->detailsBo);

        return $this->jobApplicationRepository->insert($dao);
    }

    private function logInitialStatus(int $applicationId): void
    {
        try {
            $historyDao = $this->jobApplicationHelper->prepareHistoryDao(
                $applicationId,
                null,
                JobApplicationConstants::STATUS_PENDING,
                $this->loggedInUserId,
                'Application submitted'
            );
            $this->historyRepository->insert($historyDao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to log application history: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function notifyRecruiter(int $applicationId): void
    {
        try {
            $recruiter = collect($this->userRepository->findById($this->recruiterId)->first());
            $seeker = collect($this->userRepository->findById($this->loggedInUserId)->first());

            $recruiterEmail = $recruiter->get('email');
            if (! $recruiterEmail) {
                return;
            }

            $recruiterName = trim($recruiter->get('first_name').' '.$recruiter->get('last_name'));
            $candidateName = trim($seeker->get('first_name').' '.$seeker->get('last_name'));
            $candidateEmail = $seeker->get('email', '');

            Mail::to($recruiterEmail)->send(new ApplicationSubmittedMail(
                $recruiterName,
                $this->jobTitle,
                $candidateName,
                $candidateEmail,
                $applicationId
            ));
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to send application notification: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function createRecruiterNotification(int $applicationId): void
    {
        try {
            $seeker = collect($this->userRepository->findById($this->loggedInUserId)->first());
            $candidateName = trim($seeker->get('first_name').' '.$seeker->get('last_name')) ?: 'A candidate';

            $dao = (new NotificationDAO)
                ->setUserId($this->recruiterId)
                ->setType(NotificationConstants::TYPE_APPLICATION_RECEIVED)
                ->setTitle('New application received')
                ->setBody("{$candidateName} applied for {$this->jobTitle}")
                ->setLinkId($applicationId);

            $this->notificationRepository->insert($dao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to create recruiter notification: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }
}
