<?php

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\CompanyApiController;
use App\Http\Controllers\Api\V1\JobApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - AreaKerja Mobile & Web API v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // === PUBLIC ROUTES ===
    Route::post('/auth/login', [AuthApiController::class, 'login'])->name('api.login');
    Route::post('/auth/register', [AuthApiController::class, 'registerPelamar'])->name('api.register');

    // Jobs Feed (Public search & detail)
    Route::get('/jobs', [JobApiController::class, 'index'])->name('api.jobs.index');
    Route::get('/jobs/{id}', [JobApiController::class, 'show'])->name('api.jobs.show');

    // === AUTHENTICATED ROUTES (Sanctum) ===
    Route::middleware('auth:sanctum')->group(function () {
        
        // Profile & Auth
        Route::get('/auth/profile', [AuthApiController::class, 'profile'])->name('api.profile');
        Route::post('/auth/logout', [AuthApiController::class, 'logout'])->name('api.logout');

        // Candidate Actions
        Route::post('/jobs/{id}/apply', [JobApiController::class, 'apply'])->name('api.jobs.apply');
        Route::get('/my-applications', [JobApiController::class, 'myApplications'])->name('api.jobs.myApplications');

        // Company Actions
        Route::get('/company/jobs', [CompanyApiController::class, 'myJobs'])->name('api.company.jobs');
        Route::get('/company/jobs/{id}/applicants', [CompanyApiController::class, 'jobApplicants'])->name('api.company.applicants');
        Route::put('/company/applications/{id}/status', [CompanyApiController::class, 'updateApplicantStatus'])->name('api.company.updateStatus');
    });
});
