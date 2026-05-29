<?php

namespace App\Providers;

use App\Policies\JobApplicationPolicy;
use App\Policies\JobCategoryPolicy;
use App\Policies\JobPolicy;
use App\Policies\SavedJobPolicy;
use App\Policies\UserPolicy;
use Gate;
use Illuminate\Support\ServiceProvider;

class GateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerJobGates();
        $this->registerJobApplicationGates();
        $this->registerSavedJobGates();
        $this->registerJobCategoryGates();
        $this->registerUserGates();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    private function registerJobGates(): void
    {
        Gate::define('job_add', [JobPolicy::class, 'add']);
        Gate::define('job_publish', [JobPolicy::class, 'publish']);
        Gate::define('job_edit', [JobPolicy::class, 'edit']);
        Gate::define('job_delete', [JobPolicy::class, 'delete']);
        Gate::define('job_view', [JobPolicy::class, 'view']);
    }

    private function registerSavedJobGates(): void
    {
        Gate::define('saved_job_list', [SavedJobPolicy::class, 'list']);
        Gate::define('saved_job_add', [SavedJobPolicy::class, 'add']);
        Gate::define('saved_job_delete', [SavedJobPolicy::class, 'delete']);
    }

    private function registerJobCategoryGates(): void
    {
        Gate::define('category_view', [JobCategoryPolicy::class, 'view']);
        Gate::define('category_add', [JobCategoryPolicy::class, 'add']);
        Gate::define('category_edit', [JobCategoryPolicy::class, 'edit']);
        Gate::define('category_delete', [JobCategoryPolicy::class, 'delete']);
    }

    private function registerUserGates(): void
    {
        Gate::define('user_edit', [UserPolicy::class, 'edit']);
        Gate::define('user_add', [UserPolicy::class, 'add']);
    }

    private function registerJobApplicationGates(): void
    {
        Gate::define('job_apply', [JobApplicationPolicy::class, 'apply']);
        Gate::define('application_withdraw', [JobApplicationPolicy::class, 'withdraw']);
        Gate::define('application_view', [JobApplicationPolicy::class, 'view']);
        Gate::define('application_update_status', [JobApplicationPolicy::class, 'status_update']);
        Gate::define('application_shortlist', [JobApplicationPolicy::class, 'shortlist']);
        Gate::define('application_reject', [JobApplicationPolicy::class, 'reject']);
    }
}
