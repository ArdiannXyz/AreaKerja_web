<?php

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\CompanyApiController;
use App\Http\Controllers\Api\V1\JobApiController;
use App\Http\Controllers\Api\V1\NotificationApiController;
use App\Http\Controllers\Api\V1\PaymentApiController;
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
    // --- Autentikasi & Reset Password ---
    Route::post('/auth/login', [AuthApiController::class, 'login'])->name('api.auth.login');
    Route::post('/auth/register', [AuthApiController::class, 'registerPelamar'])->name('api.auth.register');
    Route::post('/auth/forgot-password', [AuthApiController::class, 'forgotPassword'])->name('api.auth.forgotPassword');
    Route::post('/auth/verify-otp', [AuthApiController::class, 'verifyOtp'])->name('api.auth.verifyOtp');
    Route::post('/auth/reset-password', [AuthApiController::class, 'resetPassword'])->name('api.auth.resetPassword');

    // --- Jobs Feed & Pencarian Lowongan ---
    Route::get('/jobs', [JobApiController::class, 'index'])->name('api.jobs.index');
    Route::get('/jobs/{id}', [JobApiController::class, 'show'])->name('api.jobs.show');
    Route::get('/meta/filters', [JobApiController::class, 'metaFilters'])->name('api.meta.filters');

    // --- Publik Profil Perusahaan ---
    Route::get('/companies/{id}', [JobApiController::class, 'companyDetail'])->name('api.company.detail');
    Route::get('/companies/{id}/jobs', [JobApiController::class, 'companyJobs'])->name('api.company.publicJobs');

    // --- Banners & Master Data Dinamis ---
    Route::get('/banners', [JobApiController::class, 'banners'])->name('api.banners');
    Route::get('/meta/locations', [JobApiController::class, 'getLocations'])->name('api.meta.locations');
    Route::get('/meta/provinces', [JobApiController::class, 'getProvinces'])->name('api.meta.provinces');
    Route::get('/meta/cities', [JobApiController::class, 'getCities'])->name('api.meta.cities');
    Route::get('/meta/faqs', [JobApiController::class, 'faqs'])->name('api.meta.faqs');
    Route::get('/meta/legal', [JobApiController::class, 'legalContent'])->name('api.meta.legal');
    Route::get('/meta/app-version', [JobApiController::class, 'checkAppVersion'])->name('api.meta.appVersion');

    // --- Tips Kerja / Artikel ---
    Route::get('/tips-kerja', [TipsKerjaApiController::class, 'index'])->name('api.tips.index');
    Route::get('/tips-kerja/{id}', [TipsKerjaApiController::class, 'show'])->name('api.tips.show');

    // --- Paket & Payment Channels ---
    Route::get('/packages/koin', [PaymentApiController::class, 'koinPackages'])->name('api.packages.koin');
    Route::get('/packages/subscription', [PaymentApiController::class, 'subscriptionPackages'])->name('api.packages.subscription');
    Route::get('/payments/channels', [PaymentApiController::class, 'paymentChannels'])->name('api.payments.channels');
    Route::post('/payments/callback', [PaymentApiController::class, 'paymentCallback'])->name('api.payments.callback');


    /*
    |----------------------------------------------------------------------
    | 2. AUTHENTICATED ROUTES (Wajib Bearer Token: Laravel Sanctum)
    |----------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {
        
        // --- Auth & Account Management ---
        Route::get('/auth/profile', [AuthApiController::class, 'profile'])->name('api.auth.profile');
        Route::put('/auth/password', [AuthApiController::class, 'updatePassword'])->name('api.auth.password');
        Route::delete('/auth/account', [AuthApiController::class, 'deleteAccount'])->name('api.auth.deleteAccount');
        Route::post('/auth/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout');

        // --- Pelamar Profile, Skills, Avatar & CV ---
        Route::put('/pelamar/profile', [ProfileApiController::class, 'updateProfile'])->name('api.pelamar.updateProfile');
        Route::post('/pelamar/upload-avatar', [ProfileApiController::class, 'uploadAvatar'])->name('api.pelamar.uploadAvatar');
        Route::post('/pelamar/upload-cv', [ProfileApiController::class, 'uploadCv'])->name('api.pelamar.uploadCv');
        Route::post('/pelamar/upgrade-kandidat', [ProfileApiController::class, 'upgradeKandidat'])->name('api.pelamar.upgradeKandidat');
        Route::get('/pelamar/skills', [ProfileApiController::class, 'getSkills'])->name('api.pelamar.skills.get');
        Route::post('/pelamar/skills', [ProfileApiController::class, 'updateSkills'])->name('api.pelamar.skills.update');

        // --- Pelamar Experience (Pengalaman Kerja) ---
        Route::get('/pelamar/experiences', [ProfileApiController::class, 'getExperiences'])->name('api.pelamar.experiences.index');
        Route::post('/pelamar/experiences', [ProfileApiController::class, 'addExperience'])->name('api.pelamar.experiences.store');
        Route::put('/pelamar/experiences/{id}', [ProfileApiController::class, 'updateExperience'])->name('api.pelamar.experiences.update');
        Route::delete('/pelamar/experiences/{id}', [ProfileApiController::class, 'deleteExperience'])->name('api.pelamar.experiences.destroy');

        // --- Pelamar Education (Riwayat Pendidikan) ---
        Route::get('/pelamar/educations', [ProfileApiController::class, 'getEducations'])->name('api.pelamar.educations.index');
        Route::post('/pelamar/educations', [ProfileApiController::class, 'addEducation'])->name('api.pelamar.educations.store');
        Route::put('/pelamar/educations/{id}', [ProfileApiController::class, 'updateEducation'])->name('api.pelamar.educations.update');
        Route::delete('/pelamar/educations/{id}', [ProfileApiController::class, 'deleteEducation'])->name('api.pelamar.educations.destroy');

        // --- Candidate Job Applications & Saved Jobs ---
        Route::post('/jobs/{id}/apply', [JobApiController::class, 'apply'])->name('api.jobs.apply');
        Route::get('/my-applications', [JobApiController::class, 'myApplications'])->name('api.jobs.myApplications');
        Route::get('/saved-jobs', [JobApiController::class, 'getSavedJobs'])->name('api.jobs.saved');
        Route::post('/jobs/{id}/save', [JobApiController::class, 'toggleSaveJob'])->name('api.jobs.toggleSave');

        // --- Direct Job Offers (Tawaran Kerja untuk Kandidat) ---
        Route::get('/pelamar/job-offers', [ProfileApiController::class, 'getJobOffers'])->name('api.pelamar.jobOffers.index');
        Route::put('/pelamar/job-offers/{id}/respond', [ProfileApiController::class, 'respondJobOffer'])->name('api.pelamar.jobOffers.respond');

        // --- Notifications & FCM Token ---
        Route::get('/notifications', [NotificationApiController::class, 'index'])->name('api.notifications.index');
        Route::get('/notifications/unread-count', [NotificationApiController::class, 'unreadCount'])->name('api.notifications.unreadCount');
        Route::put('/notifications/{id}/read', [NotificationApiController::class, 'markAsRead'])->name('api.notifications.markAsRead');
        Route::put('/notifications/mark-all-read', [NotificationApiController::class, 'markAllAsRead'])->name('api.notifications.markAllRead');
        Route::delete('/notifications/{id}', [NotificationApiController::class, 'destroy'])->name('api.notifications.destroy');
        Route::delete('/notifications', [NotificationApiController::class, 'destroyAll'])->name('api.notifications.destroyAll');
        Route::post('/notifications/device-token', [NotificationApiController::class, 'updateDeviceToken'])->name('api.notifications.deviceToken');

        // --- Company Module ---
        Route::prefix('company')->group(function () {
            Route::get('/dashboard', [CompanyApiController::class, 'dashboardSummary'])->name('api.company.dashboard');
            Route::get('/profile', [CompanyApiController::class, 'getProfile'])->name('api.company.getProfile');
            Route::put('/profile', [CompanyApiController::class, 'updateProfile'])->name('api.company.updateProfile');
            Route::post('/upload-logo', [CompanyApiController::class, 'uploadLogo'])->name('api.company.uploadLogo');

            // CRUD Company Jobs
            Route::get('/jobs', [CompanyApiController::class, 'myJobs'])->name('api.company.jobs');
            Route::post('/jobs', [CompanyApiController::class, 'storeJob'])->name('api.company.storeJob');
            Route::get('/jobs/{id}', [CompanyApiController::class, 'showJob'])->name('api.company.showJob');
            Route::put('/jobs/{id}', [CompanyApiController::class, 'updateJob'])->name('api.company.updateJob');
            Route::delete('/jobs/{id}', [CompanyApiController::class, 'deleteJob'])->name('api.company.deleteJob');
            Route::patch('/jobs/{id}/toggle-status', [CompanyApiController::class, 'toggleJobStatus'])->name('api.company.toggleStatus');

            // Applicants & Status
            Route::get('/jobs/{id}/applicants', [CompanyApiController::class, 'jobApplicants'])->name('api.company.applicants');
            Route::put('/applications/{id}/status', [CompanyApiController::class, 'updateApplicantStatus'])->name('api.company.updateStatus');

            // Talent Hunter
            Route::get('/talents', [CompanyApiController::class, 'talents'])->name('api.company.talents');
            Route::get('/talents/{id}', [CompanyApiController::class, 'talentDetail'])->name('api.company.talentDetail');
            Route::post('/talents/{id}/offer', [CompanyApiController::class, 'sendJobOffer'])->name('api.company.sendOffer');
        });

        // --- Payment & Transactions ---
        Route::post('/payments/checkout', [PaymentApiController::class, 'checkout'])->name('api.payments.checkout');
        Route::get('/payments/history', [PaymentApiController::class, 'history'])->name('api.payments.history');
        Route::get('/payments/{id}', [PaymentApiController::class, 'showPayment'])->name('api.payments.show');
    });
});
