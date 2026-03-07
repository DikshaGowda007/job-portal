<?php

use App\Constants\UserConstant;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Job\JobController;

Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'signup'])->name('UserController.signup');
    Route::post('/login', [UserController::class, 'login'])->name('UserController.login');
    Route::post('/verifyOtp', [UserController::class, 'verifyOtp'])->name('verifyOtp');
});

Route::prefix('job')->middleware(['jwt.verify', 'access.role:' . UserConstant::USER_ROLE_ADMIN . '|' . UserConstant::USER_ROLE_SUB_ADMIN . '|' . UserConstant::USER_ROLE_RECRUITER . '|' . UserConstant::USER_ROLE_JOB_SEEKER])->group(function () {
    Route::post('/add', [JobController::class, 'add'])->name('JobController.add');
    Route::post('/edit', [JobController::class, 'edit'])->name('JobController.edit');
    Route::post('/delete', [JobController::class, 'delete'])->name('JobController.delete');
    Route::post('/list', [JobController::class, 'list'])->name('JobController.list');
    Route::post('/get', [JobController::class, 'get'])->name('JobController.get');
});