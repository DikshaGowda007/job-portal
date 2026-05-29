<?php

namespace App\Modules\V1\JobApplication\Services\Withdraw;

use App\Constants\CommonConstant;
use App\Constants\EmailConstant;
use App\Constants\JobApplicationConstants;
use App\Constants\JobConstants;
use App\Dto\WithdrawJobApplicationMailDto;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\InvalidJobException;
use App\Mail\RecruiterWithdrawJobApplicationMail;
use App\Mail\SeekerWithdrawJobApplicationMail;
use App\Modules\V1\Email\Bo\TemplateBo;
use App\Modules\V1\Email\Services\TemplateService;
use App\Repositories\DAO\V1\JobApplicationDAO;
use App\Repositories\DAO\V1\JobApplicationHistoryDAO;
use App\Repositories\V1\JobApplicationHistoryRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    private ?Collection $application = null;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
        private JobApplicationHistoryRepository $jobApplicationHistoryRepository,
        private JobApplicationDAO $jobApplicationDao
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function withdraw(int $applicationId): JsonResponse
    {
        try {
            $this->findApplication($applicationId);
            $this->hasWithdrawAccess();
            $this->validateApplicationStatus();
            $this->withdrawJob($applicationId);
            $this->sendWithdrawalNotification();

            return response()->json(CommonUtils::successResponse('Application withdrawn successfully'));
        } catch (DataNotFoundException|InvalidJobException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to withdraw application'));
        }
    }

    private function findApplication(int $applicationId): void
    {
        $applicationDetails = collect($this->jobApplicationRepository->findByIdWithJobPostAndUser($applicationId)->first());
        if ($applicationDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        $this->application = $applicationDetails;
    }

    private function hasWithdrawAccess(): void
    {
        if ($this->loggedInUserId !== $this->application->get('user')['id']) {
            throw AccessForbiddenException::withMessage('Unauthorized to withdraw this application');
        }
    }

    private function validateApplicationStatus()
    {
        if (in_array($this->application->get('job_post')['status'], [JobConstants::STATUS_CLOSED, JobConstants::STATUS_EXPIRED])) {
            throw InvalidJobException::withMessage('Job already '.strtolower($this->application->get('job_post')['status']));
        }
    }

    private function withdrawJob(int $applicationId): void
    {
        $this->jobApplicationDao->setStatus(JobApplicationConstants::STATUS_WITHDRAWN);

        $this->jobApplicationRepository->updateById($applicationId, $this->jobApplicationDao);
        $this->logApplicationHistory($applicationId, $this->jobApplicationDao->getStatus());
    }

    private function sendWithdrawalNotification(): void
    {
        try {
            // Send email to seeker (candidate)
            $seekerTemplateBo = $this->prepareSeekerTemplateBo();
            $seekerEmailDto = $this->prepareSeekerEmailDto();

            $templateService = app(TemplateService::class);
            $templateService->initiateEmailProcess($seekerTemplateBo, $seekerEmailDto);

            // Send email to recruiter
            // $recruiterTemplateBo = $this->prepareRecruiterTemplateBo();
            // $recruiterEmailDto = $this->prepareRecruiterEmailDto();
            // $templateService->initiateEmailProcess($recruiterTemplateBo, $recruiterEmailDto);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function prepareSeekerTemplateBo(): TemplateBo
    {
        $templateBo = new TemplateBo;

        $templateBo->setTemplateCode('SEEKER_WITHDRAW_JOB_APPLICATION_EMAIL');
        $templateBo->setModuleType(EmailConstant::MODULE_TYPE_GENERAL);
        $templateBo->setEmailId($this->application->get('user')['email']);
        $templateBo->setMailableClass(SeekerWithdrawJobApplicationMail::class);
        $templateBo->setMailableDtoClass(WithdrawJobApplicationMailDto::class);
        $templateBo->setUserId($this->application->get('user')['id']);

        return $templateBo;
    }

    private function prepareSeekerEmailDto(): WithdrawJobApplicationMailDto
    {
        $emailDto = new WithdrawJobApplicationMailDto;

        $emailDto->setJobTitle($this->application->get('job_post')['title']);
        $emailDto->setCandidateName($this->application->get('user')['first_name'].' '.$this->application->get('user')['last_name']);
        $emailDto->setCandidateEmail($this->application->get('user')['email']);
        $emailDto->setApplicationId($this->application->get('id'));

        return $emailDto;
    }

    private function prepareRecruiterTemplateBo(): TemplateBo
    {
        $templateBo = new TemplateBo;

        $templateBo->setTemplateCode('RECRUITER_WITHDRAW_JOB_APPLICATION_EMAIL');
        $templateBo->setModuleType(EmailConstant::MODULE_TYPE_GENERAL);
        $templateBo->setEmailId($this->application->get('job_post')['recruiter']['email']);
        $templateBo->setMailableClass(RecruiterWithdrawJobApplicationMail::class);
        $templateBo->setMailableDtoClass(WithdrawJobApplicationMailDto::class);
        $templateBo->setUserId($this->application->get('job_post')['recruiter']['id']);

        return $templateBo;
    }

    private function prepareRecruiterEmailDto(): WithdrawJobApplicationMailDto
    {
        $emailDto = new WithdrawJobApplicationMailDto;

        $emailDto->setRecruiterName($this->application->get('job_post')['recruiter']['first_name'].' '.$this->application->get('job_post')['recruiter']['last_name']);
        $emailDto->setJobTitle($this->application->get('job_post')['title']);
        $emailDto->setCandidateName($this->application->get('user')['first_name'].' '.$this->application->get('user')['last_name']);
        $emailDto->setCandidateEmail($this->application->get('user')['email']);
        $emailDto->setApplicationId($this->application->get('id'));

        return $emailDto;
    }

    private function logApplicationHistory(int $applicationId, string $oldStatus): void
    {
        $jobApplicationHistoryDao = new JobApplicationHistoryDAO;

        $jobApplicationHistoryDao->setJobApplicationId($applicationId);
        $jobApplicationHistoryDao->setPreviousStatus($oldStatus);
        $jobApplicationHistoryDao->setNewStatus(JobApplicationConstants::STATUS_WITHDRAWN);
        $jobApplicationHistoryDao->setChangedBy($this->loggedInActionByUserId);
        $jobApplicationHistoryDao->setNotes('Application withdrawn by candidate');

        $this->jobApplicationHistoryRepository->insert($jobApplicationHistoryDao);
    }
}
