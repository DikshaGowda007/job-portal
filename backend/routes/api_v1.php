<?php

use App\Constants\UserConstant;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\JobApplication\JobApplicationController;

Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'signup'])->name('UserController.signup');
    Route::post('/login', [UserController::class, 'login'])->name('UserController.login');
    Route::post('/verifyOtp', [UserController::class, 'verifyOtp'])->name('verifyOtp');
    Route::post('/resend-otp', [UserController::class, 'resendOtp'])->name('UserController.resendOtp')->middleware('throttle:3,1');
});

Route::prefix('job')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN.'|'.UserConstant::USER_ROLE_RECRUITER.'|'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/add', [JobController::class, 'add'])->name('JobController.add');
    Route::post('/publish', [JobController::class, 'publish'])->name('JobController.publish');
    Route::post('/edit', [JobController::class, 'edit'])->name('JobController.edit');
    Route::post('/delete', [JobController::class, 'delete'])->name('JobController.delete');
    Route::post('/list', [JobController::class, 'list'])->name('JobController.list');
    Route::post('/get', [JobController::class, 'get'])->name('JobController.get');
});

Route::prefix('application')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN.'|'.UserConstant::USER_ROLE_RECRUITER])->group(function () {
    Route::post('/list', [JobApplicationController::class, 'list'])->name('JobApplicationController.list');
    Route::post('/my-applications', [JobApplicationController::class, 'myApplications'])->name('JobApplicationController.myApplications');
    Route::post('/view', [JobApplicationController::class, 'view'])->name('JobApplicationController.view');
    Route::post('/update-status', [JobApplicationController::class, 'updateStatus'])->name('JobApplicationController.updateStatus');
});

// Job Seeker operations
Route::prefix('application')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/apply', [JobApplicationController::class, 'apply'])->name('JobApplicationController.apply');
    Route::post('/my-applications', [JobApplicationController::class, 'myApplications'])->name('JobApplicationController.myApplications');
    Route::post('/withdraw', [JobApplicationController::class, 'withdraw'])->name('JobApplicationController.withdraw');
});
