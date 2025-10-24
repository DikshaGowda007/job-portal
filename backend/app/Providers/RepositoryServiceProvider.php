<?php

namespace App\Providers;

use App\Repositories\MySql\V1\AllUserAccessRightRepositoryImpl;
use App\Repositories\MySql\V1\UserOTPVerificationRepositoryImpl;
use App\Repositories\MySql\V1\UserRepositoryImpl;
use App\Repositories\V1\AllUserAccessRightRepository;
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
        $this->app->bind(AllUserAccessRightRepository::class, AllUserAccessRightRepositoryImpl::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
