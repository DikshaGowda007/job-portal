<?php

use App\Constants\UserConstant;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\JobApplication\JobApplicationController;
use App\Http\Controllers\SavedJob\SavedJobController;

Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'signup'])->name('UserController.signup');
    Route::post('/login', [UserController::class, 'login'])->name('UserController.login');
    Route::post('/verifyOtp', [UserController::class, 'verifyOtp'])->name('verifyOtp');
    Route::post('/resend-otp', [UserController::class, 'resendOtp'])->name('UserController.resendOtp')->middleware('throttle:3,1');
});

Route::prefix('auth')->middleware(['jwt.verify'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('UserController.logout');
});

Route::prefix('job')->group(function () {
    Route::post('/get', [JobController::class, 'get'])->name('JobController.get');
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
    Route::post('/get', [JobApplicationController::class, 'get'])->name('JobApplicationController.get');
    Route::post('/withdraw', [JobApplicationController::class, 'withdraw'])->name('JobApplicationController.withdraw');
});

Route::prefix('saved-job')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/add', [SavedJobController::class, 'add'])->name('SavedJobController.add');
    Route::post('/list', [SavedJobController::class, 'list'])->name('SavedJobController.list');
    Route::post('/delete', [SavedJobController::class, 'delete'])->name('SavedJobController.delete');
});

Route::prefix('profile')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/get', [JobSeekerProfileController::class, 'get'])->name('JobSeekerProfileController.get');
    Route::post('/update', [JobSeekerProfileController::class, 'update'])->name('JobSeekerProfileController.update');
    Route::post('/experience/add', [JobSeekerProfileController::class, 'addExperience'])->name('JobSeekerProfileController.addExperience');
    Route::post('/experience/update', [JobSeekerProfileController::class, 'updateExperience'])->name('JobSeekerProfileController.updateExperience');
    Route::post('/experience/delete', [JobSeekerProfileController::class, 'deleteExperience'])->name('JobSeekerProfileController.deleteExperience');
    Route::post('/education/add', [JobSeekerProfileController::class, 'addEducation'])->name('JobSeekerProfileController.addEducation');
    Route::post('/education/update', [JobSeekerProfileController::class, 'updateEducation'])->name('JobSeekerProfileController.updateEducation');
    Route::post('/education/delete', [JobSeekerProfileController::class, 'deleteEducation'])->name('JobSeekerProfileController.deleteEducation');
});
