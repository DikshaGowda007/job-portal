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

    private function registerJobApplicationGates(): void
    {
        Gate::define('job_apply', [JobApplicationPolicy::class, 'apply']);
        Gate::define('application_withdraw', [JobApplicationPolicy::class, 'withdraw']);
        Gate::define('application_view', [JobApplicationPolicy::class, 'view']);
    }
}
