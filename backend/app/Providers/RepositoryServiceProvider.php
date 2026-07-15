<?php

namespace App\Providers;

use App\Repositories\MySql\V1\AllUserAccessRightRepositoryImpl;
use App\Repositories\MySql\V1\ApplicationMessageRepositoryImpl;
use App\Repositories\MySql\V1\EmailLogsRepositoryImpl;
use App\Repositories\MySql\V1\EmailTemplateRepositoryImpl;
use App\Repositories\MySql\V1\JobApplicationHistoryRepositoryImpl;
use App\Repositories\MySql\V1\JobApplicationRepositoryImpl;
use App\Repositories\MySql\V1\JobCategoryRepositoryImpl;
use App\Repositories\MySql\V1\JobRepositoryImpl;
use App\Repositories\MySql\V1\JobSeekerEducationRepositoryImpl;
use App\Repositories\MySql\V1\JobSeekerExperienceRepositoryImpl;
use App\Repositories\MySql\V1\JobSeekerProfileRepositoryImpl;
use App\Repositories\MySql\V1\MessageLogRepositoryImpl;
use App\Repositories\MySql\V1\NotificationRepositoryImpl;
use App\Repositories\MySql\V1\RecruiterProfileRepositoryImpl;
use App\Repositories\MySql\V1\SavedJobRepositoryImpl;
use App\Repositories\MySql\V1\UserOTPVerificationRepositoryImpl;
use App\Repositories\MySql\V1\UserRepositoryImpl;
use App\Repositories\V1\AllUserAccessRightRepository;
use App\Repositories\V1\ApplicationMessageRepository;
use App\Repositories\V1\EmailLogsRepository;
use App\Repositories\V1\EmailTemplateRepository;
use App\Repositories\V1\JobApplicationHistoryRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\JobCategoryRepository;
use App\Repositories\V1\JobRepository;
use App\Repositories\V1\JobSeekerEducationRepository;
use App\Repositories\V1\JobSeekerExperienceRepository;
use App\Repositories\V1\JobSeekerProfileRepository;
use App\Repositories\V1\MessageLogRepository;
use App\Repositories\V1\NotificationRepository;
use App\Repositories\V1\RecruiterProfileRepository;
use App\Repositories\V1\SavedJobRepository;
use App\Repositories\V1\UserOTPVerificationRepository;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
        $this->app->bind(UserOTPVerificationRepository::class, UserOTPVerificationRepositoryImpl::class);
        $this->app->bind(UserOTPVerificationRepository::class, UserOTPVerificationRepositoryImpl::class);
        $this->app->bind(JobRepository::class, JobRepositoryImpl::class);
        $this->app->bind(JobApplicationRepository::class, JobApplicationRepositoryImpl::class);
        $this->app->bind(JobApplicationHistoryRepository::class, JobApplicationHistoryRepositoryImpl::class);
        $this->app->bind(ApplicationMessageRepository::class, ApplicationMessageRepositoryImpl::class);
        $this->app->bind(JobSeekerProfileRepository::class, JobSeekerProfileRepositoryImpl::class);
        $this->app->bind(JobSeekerExperienceRepository::class, JobSeekerExperienceRepositoryImpl::class);
        $this->app->bind(JobSeekerEducationRepository::class, JobSeekerEducationRepositoryImpl::class);
        $this->app->bind(AllUserAccessRightRepository::class, AllUserAccessRightRepositoryImpl::class);
        $this->app->bind(SavedJobRepository::class, SavedJobRepositoryImpl::class);
        $this->app->bind(JobCategoryRepository::class, JobCategoryRepositoryImpl::class);
        $this->app->bind(EmailTemplateRepository::class, EmailTemplateRepositoryImpl::class);
        $this->app->bind(EmailLogsRepository::class, EmailLogsRepositoryImpl::class);
        $this->app->bind(MessageLogRepository::class, MessageLogRepositoryImpl::class);
        $this->app->bind(NotificationRepository::class, NotificationRepositoryImpl::class);
        $this->app->bind(RecruiterProfileRepository::class, RecruiterProfileRepositoryImpl::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
