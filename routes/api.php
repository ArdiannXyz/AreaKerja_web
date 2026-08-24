<?php

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\CompanyApiController;
use App\Http\Controllers\Api\V1\JobApiController;
use App\Http\Controllers\Api\V1\NotificationApiController;
use App\Http\Controllers\Api\V1\ProfileApiController;
use App\Http\Controllers\Api\V1\TipsKerjaApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - AreaKerja Mobile & Web API v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |----------------------------------------------------------------------
    | 1. PUBLIC ROUTES (Tanpa Autentikasi)
    |----------------------------------------------------------------------
    */
    // Authentication
    Route::post('/auth/login', [AuthApiController::class, 'login'])->name('api.auth.login');
    Route::post('/auth/register', [AuthApiController::class, 'registerPelamar'])->name('api.auth.register');

    // Jobs Feed (Pencarian & Detail Lowongan)
    Route::get('/jobs', [JobApiController::class, 'index'])->name('api.jobs.index');
    Route::get('/jobs/{id}', [JobApiController::class, 'show'])->name('api.jobs.show');

    // Tips Kerja / Artikel
    Route::get('/tips-kerja', [TipsKerjaApiController::class, 'index'])->name('api.tips.index');
    Route::get('/tips-kerja/{id}', [TipsKerjaApiController::class, 'show'])->name('api.tips.show');


    /*
    |----------------------------------------------------------------------
    | 2. AUTHENTICATED ROUTES (Wajib Bearer Token: Laravel Sanctum)
    |----------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {
        
        // --- Auth & Session ---
        Route::get('/auth/profile', [AuthApiController::class, 'profile'])->name('api.auth.profile');
        Route::post('/auth/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout');

        // --- Pelamar Profile & CV ---
        Route::put('/pelamar/profile', [ProfileApiController::class, 'updateProfile'])->name('api.pelamar.updateProfile');
        Route::post('/pelamar/upload-cv', [ProfileApiController::class, 'uploadCv'])->name('api.pelamar.uploadCv');

        // --- Candidate Job Applications ---
        Route::post('/jobs/{id}/apply', [JobApiController::class, 'apply'])->name('api.jobs.apply');
        Route::get('/my-applications', [JobApiController::class, 'myApplications'])->name('api.jobs.myApplications');

        // --- Candidate Saved / Bookmark Jobs ---
        Route::get('/saved-jobs', [JobApiController::class, 'getSavedJobs'])->name('api.jobs.saved');
        Route::post('/jobs/{id}/save', [JobApiController::class, 'toggleSaveJob'])->name('api.jobs.toggleSave');

        // --- Notifications ---
        Route::get('/notifications', [NotificationApiController::class, 'index'])->name('api.notifications.index');
        Route::get('/notifications/unread-count', [NotificationApiController::class, 'unreadCount'])->name('api.notifications.unreadCount');
        Route::put('/notifications/{id}/read', [NotificationApiController::class, 'markAsRead'])->name('api.notifications.markAsRead');
        Route::put('/notifications/mark-all-read', [NotificationApiController::class, 'markAllAsRead'])->name('api.notifications.markAllRead');

        // --- Company Features ---
        Route::prefix('company')->group(function () {
            Route::get('/dashboard', [CompanyApiController::class, 'dashboardSummary'])->name('api.company.dashboard');
            Route::get('/jobs', [CompanyApiController::class, 'myJobs'])->name('api.company.jobs');
            Route::post('/jobs', [CompanyApiController::class, 'storeJob'])->name('api.company.storeJob');
            Route::get('/jobs/{id}/applicants', [CompanyApiController::class, 'jobApplicants'])->name('api.company.applicants');
            Route::put('/applications/{id}/status', [CompanyApiController::class, 'updateApplicantStatus'])->name('api.company.updateStatus');
        });
    });
});
