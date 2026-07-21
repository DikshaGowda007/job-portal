<?php

use App\Constants\UserConstant;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\JobAlert\JobAlertController;
use App\Http\Controllers\JobApplication\JobApplicationController;
use App\Http\Controllers\JobCategory\JobCategoryController;
use App\Http\Controllers\JobSeekerProfile\JobSeekerProfileController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Recruiter\RecruiterController;
use App\Http\Controllers\RecruiterProfile\RecruiterProfileController;
use App\Http\Controllers\SavedJob\SavedJobController;
use App\Http\Controllers\User\ProfileController;

Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'signup'])->name('UserController.signup')->middleware('throttle:10,1');
    Route::post('/login', [UserController::class, 'login'])->name('UserController.login')->middleware('throttle:5,1');
    Route::post('/verifyOtp', [UserController::class, 'verifyOtp'])->name('verifyOtp')->middleware('throttle:10,1');
    Route::post('/resend-otp', [UserController::class, 'resendOtp'])->name('UserController.resendOtp')->middleware('throttle:3,1');
    Route::post('/forgot-password', [UserController::class, 'forgotPassword'])->name('UserController.forgotPassword')->middleware('throttle:5,1');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('UserController.resetPassword')->middleware('throttle:5,1');
});

Route::prefix('auth')->middleware(['jwt.verify'])->group(function () {
    Route::post('/refresh', [UserController::class, 'refresh'])->name('UserController.refresh');
    Route::post('/logout', [UserController::class, 'logout'])->name('UserController.logout');
});

// User profile routes (authenticated)
Route::prefix('user')->middleware(['jwt.verify'])->group(function () {
    Route::post('/me', [ProfileController::class, 'me'])->name('ProfileController.me');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('ProfileController.updateProfile');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('ProfileController.changePassword');
    Route::post('/access-rights/get', [UserController::class, 'getAccessRights'])->name('UserController.getAccessRights');
    Route::post('/access-rights/edit', [UserController::class, 'editAccessRights'])->name('UserController.editAccessRights');
});

// Public — no token required
Route::prefix('job')->group(function () {
    Route::post('/list', [JobController::class, 'list'])->name('JobController.list');
    Route::post('/get', [JobController::class, 'get'])->name('JobController.get');
    Route::post('/suggestions', [JobController::class, 'suggestions'])->name('JobController.suggestions');
});

// Authenticated job write operations
Route::prefix('job')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN.'|'.UserConstant::USER_ROLE_RECRUITER.'|'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/add', [JobController::class, 'add'])->name('JobController.add');
    Route::post('/publish', [JobController::class, 'publish'])->name('JobController.publish');
    Route::post('/edit', [JobController::class, 'edit'])->name('JobController.edit');
    Route::post('/delete', [JobController::class, 'delete'])->name('JobController.delete');
});

// Recruiter + Admin: view and manage all applications
Route::prefix('application')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN.'|'.UserConstant::USER_ROLE_RECRUITER])->group(function () {
    Route::post('/list', [JobApplicationController::class, 'list'])->name('JobApplicationController.list');
    Route::post('/view', [JobApplicationController::class, 'view'])->name('JobApplicationController.view');
    Route::post('/history', [JobApplicationController::class, 'history'])->name('JobApplicationController.history');
    Route::post('/update-status', [JobApplicationController::class, 'updateStatus'])->name('JobApplicationController.updateStatus');
});

// Job Seeker operations
Route::prefix('application')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/apply', [JobApplicationController::class, 'apply'])->name('JobApplicationController.apply');
    Route::post('/my-applications', [JobApplicationController::class, 'myApplications'])->name('JobApplicationController.myApplications');
    Route::post('/get', [JobApplicationController::class, 'get'])->name('JobApplicationController.get');
    Route::post('/withdraw', [JobApplicationController::class, 'withdraw'])->name('JobApplicationController.withdraw');
    Route::post('/history', [JobApplicationController::class, 'history'])->name('JobApplicationController.seekerHistory');
    Route::post('/conversations', [JobApplicationController::class, 'conversations'])->name('JobApplicationController.conversations');
    Route::post('/send-message', [JobApplicationController::class, 'sendMessage'])->name('JobApplicationController.sendMessage');
    Route::post('/mark-read', [JobApplicationController::class, 'markRead'])->name('JobApplicationController.markRead');
    Route::post('/typing', [JobApplicationController::class, 'typing'])->name('JobApplicationController.typing');
});

Route::prefix('recruiter')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_RECRUITER.'|'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN])->group(function () {
    Route::post('/dashboard', [RecruiterController::class, 'dashboard'])->name('RecruiterController.dashboard');
    Route::post('/my-jobs', [RecruiterController::class, 'myJobs'])->name('RecruiterController.myJobs');
    Route::post('/my-applications', [RecruiterController::class, 'myApplications'])->name('RecruiterController.myApplications');
    Route::post('/conversations', [JobApplicationController::class, 'recruiterConversations'])->name('JobApplicationController.recruiterConversations');
    Route::post('/send-message', [JobApplicationController::class, 'recruiterSendMessage'])->name('JobApplicationController.recruiterSendMessage');
    Route::post('/mark-read', [JobApplicationController::class, 'markRead'])->name('JobApplicationController.recruiterMarkRead');
    Route::post('/typing', [JobApplicationController::class, 'typing'])->name('JobApplicationController.recruiterTyping');
});

Route::prefix('category')->middleware(['jwt.verify'])->group(function () {
    Route::post('/list', [JobCategoryController::class, 'list'])->name('JobCategoryController.list');
    Route::post('/get', [JobCategoryController::class, 'get'])->name('JobCategoryController.get');
});

Route::prefix('category')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN])->group(function () {
    Route::post('/add', [JobCategoryController::class, 'add'])->name('JobCategoryController.add');
    Route::post('/edit', [JobCategoryController::class, 'edit'])->name('JobCategoryController.edit');
    Route::post('/delete', [JobCategoryController::class, 'delete'])->name('JobCategoryController.delete');
});

Route::prefix('saved-job')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/add', [SavedJobController::class, 'add'])->name('SavedJobController.add');
    Route::post('/list', [SavedJobController::class, 'list'])->name('SavedJobController.list');
    Route::post('/delete', [SavedJobController::class, 'delete'])->name('SavedJobController.delete');
});

Route::prefix('job-alert')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/add', [JobAlertController::class, 'add'])->name('JobAlertController.add');
    Route::post('/edit', [JobAlertController::class, 'edit'])->name('JobAlertController.edit');
    Route::post('/list', [JobAlertController::class, 'list'])->name('JobAlertController.list');
    Route::post('/delete', [JobAlertController::class, 'delete'])->name('JobAlertController.delete');
});

// Job Seeker Profile
Route::prefix('profile')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/get', [JobSeekerProfileController::class, 'get'])->name('JobSeekerProfileController.get');
    Route::post('/update', [JobSeekerProfileController::class, 'update'])->name('JobSeekerProfileController.update');
    Route::post('/upload-resume', [JobSeekerProfileController::class, 'uploadResume'])->name('JobSeekerProfileController.uploadResume');
    Route::post('/experience/add', [JobSeekerProfileController::class, 'addExperience'])->name('JobSeekerProfileController.addExperience');
    Route::post('/experience/update', [JobSeekerProfileController::class, 'updateExperience'])->name('JobSeekerProfileController.updateExperience');
    Route::post('/experience/delete', [JobSeekerProfileController::class, 'deleteExperience'])->name('JobSeekerProfileController.deleteExperience');
    Route::post('/education/add', [JobSeekerProfileController::class, 'addEducation'])->name('JobSeekerProfileController.addEducation');
    Route::post('/education/update', [JobSeekerProfileController::class, 'updateEducation'])->name('JobSeekerProfileController.updateEducation');
    Route::post('/education/delete', [JobSeekerProfileController::class, 'deleteEducation'])->name('JobSeekerProfileController.deleteEducation');
});

Route::prefix('profile')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_RECRUITER.'|'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN])->group(function () {
    Route::post('/view', [JobSeekerProfileController::class, 'view'])->name('JobSeekerProfileController.view');
});

Route::prefix('recruiter/company-profile')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_RECRUITER])->group(function () {
    Route::post('/get', [RecruiterProfileController::class, 'get'])->name('RecruiterProfileController.get');
    Route::post('/update', [RecruiterProfileController::class, 'update'])->name('RecruiterProfileController.update');
});

Route::prefix('notification')->middleware(['jwt.verify'])->group(function () {
    Route::post('/list', [NotificationController::class, 'list'])->name('NotificationController.list');
    Route::post('/mark-read', [NotificationController::class, 'markRead'])->name('NotificationController.markRead');
});

// Admin Dashboard Routes
Route::prefix('admin')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN.'|'.UserConstant::USER_ROLE_SUB_ADMIN])->group(function () {
    Route::post('/dashboard', [AdminController::class, 'dashboard'])->name('AdminController.dashboard');
    Route::post('/users/list', [AdminController::class, 'listUsers'])->name('AdminController.listUsers');
    Route::post('/users/view', [AdminController::class, 'viewUser'])->name('AdminController.viewUser');
    Route::post('/users/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('AdminController.toggleUserStatus');
});

// Admin-only routes
Route::prefix('admin')->middleware(['jwt.verify', 'access.role:'.UserConstant::USER_ROLE_ADMIN])->group(function () {
    Route::post('/sub-admin/create', [AdminController::class, 'createSubAdmin'])->name('AdminController.createSubAdmin');
    Route::post('/sub-admin/list', [AdminController::class, 'listSubAdmins'])->name('AdminController.listSubAdmins');
});
