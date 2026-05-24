<?php

namespace App\Providers;

use App\Policies\JobApplicationPolicy;
use App\Policies\JobPolicy;
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
        Gate::define('job_publish', [JobPolicy::class, 'publish']);
        Gate::define('job_edit', [JobPolicy::class, 'edit']);
        Gate::define('job_delete', [JobPolicy::class, 'delete']);
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

    private function registerJobApplicationGates(): void
    {
        Gate::define('job_apply', [JobApplicationPolicy::class, 'apply']);
        Gate::define('application_withdraw', [JobApplicationPolicy::class, 'withdraw']);
        Gate::define('application_view', [JobApplicationPolicy::class, 'view']);
        Gate::define('application_update_status', [JobApplicationPolicy::class, 'status_update']);
    }
}
