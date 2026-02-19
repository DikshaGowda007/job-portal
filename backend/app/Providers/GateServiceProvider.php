<?php

namespace App\Providers;

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

    }
}
