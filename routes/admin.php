<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BantuanChatController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IklanAdminController;
use App\Http\Controllers\ModerasiReportController;
use App\Http\Controllers\LowonganPerusahaanController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\TipsKerjaController;
use App\Http\Controllers\VerifikasiPerusahaanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->middleware('auth')->group(function () {
    Route::post('/logout/admin', 'logout_admin')->name('logout_admin');
});

Route::prefix('admin')->middleware('auth', 'role:admin', 'CheckUserStatus')->group(function () {

    // AUTH CONTROLLER
    Route::controller(AuthController::class)->group(function () {
        Route::get('/dashboard', 'beranda_admin')->name('admin.dashboard');
    });

    // ADMIN CONTROLLER
    Route::controller(AdminController::class)->group(function () {
        // profile admin
        Route::get('/profile', 'profile_admin')->name('admin.profile');
        Route::get('/edit/profile', 'edit_profile')->name('admin.edit.profile');
        Route::put('/update/profile/{admin:id}', 'update_profile_admin')->name('admin.update.profile');
        Route::delete('/delete/profile/{admin:id}', 'destroy_profile')->name('admin.destroy.profile');

        // calon kandidat
        Route::get('/calon/kandidat', 'halCalonKandidat')->name('admin.calon-kandidat');
        Route::get('/calon-kandidat/{id}', 'detailCalonKandidat')->name('calon.detail');
        Route::post('/calon-kandidat/{id}/update', 'updateTraining')->name('calon.update');
        Route::post('/calon-kandidat/{id}/lulus', 'lulus')->name('calon.lulus');
        Route::post('/calon-kandidat/{id}/gugur', 'gugur')->name('calon.gugur');

        // non kandidat
        Route::get('/non/kandidat', 'halNonKandidat')->name('admin.non-kandidat');
        Route::get('/non-kandidat/{id}', 'detailNonKandidat')->name('admin.detail.non.kandidat');

        // kandidat
        Route::get('/kandidat', 'halKandidat')->name('admin.kandidat');
        Route::get('/kandidat/{id}', 'detailKandidat')->name('admin.detail.kandidat');

        // finance redirect
        Route::get('/finance', function () {
            return redirect()->route('admin.dashboard')->with('info', 'Menu keuangan saat ini dikelola terpusat oleh role Finance.');
        })->name('admin.finance');
        Route::get('/finance/tunai', function () {
            return redirect()->route('admin.dashboard')->with('info', 'Menu keuangan saat ini dikelola terpusat oleh role Finance.');
        })->name('admin.finance.cash');

        // provinsi kota kecamatan
        Route::get('/get-kota/{provinsi_id}', 'getKota')->name('admin.get.kota');
        Route::get('/get-kecamatan/{kota_id}', 'getKecamatan')->name('admin.get.kecamatan');

        // perusahaan admin
        Route::get('/perusahaan', 'halPerusahaan')->name('admin.perusahaan');
        Route::get('/perusahaan/detail/{id}', 'detailPerusahaan')->name('admin.perusahaan.detail');
        Route::get('/admin/lowongan/{perusahaan}/{lowongan}', 'detailLowongan')->name('admin.lowongan.detail');
        Route::get('/perusahaan/talent/hunter', function () {
            return view('perusahaan.talenthunter-perusahaan');
        })->name('admin.perusahaan.talent-hunter');

        // freeze akun
        Route::post('/user/freeze/{id}', 'bekukan')->name('admin.freeze');
        Route::post('/user/unfreeze/{id}', 'aktifkan')->name('admin.unfreeze');

        // talent hunter
        Route::get('/talent/hunter', 'talentHunterForm')->name('admin.talent-hunter');
        Route::get('/talent/hunter/detail/{id}', 'detailTalentHunter')->name('admin.talent-hunter.detail');

        // recruitment
        Route::get('/recruitment/perusahaan', 'halPerusahaanRecruitment')->name('admin.recruitment.perusahaan');
        Route::get('/recruitment/{id}', 'recruitment')->name('admin.recruitment');
        Route::get('/recruitment/{id}/detail', 'detailRecruitment')->name('admin.recruitment.detail');
        Route::delete('/recruitment/{id}/hapus', 'destroyRecruitment')->name('admin.recruitment.destroy');

        // FILTER PROVINSI
        Route::get('/dashboard/pilih-provinsi', 'pilihProvinsi')->name('dashboard.pilih-provinsi');
        Route::post('/dashboard/set-provinsi', 'setProvinsi')->name('dashboard.set-provinsi');
    });

    // TIPS KERJA CONTROLLER
    Route::controller(TipsKerjaController::class)->group(function () {
        Route::get('/tips/kerja', 'index')->name('admin.tips-kerja');
        Route::post('/tips/kerja/', 'store_tips_kerja')->name('admin.tips-kerja.store');
        Route::get('/tips/kerja/create', 'tips_kerja_buat_post')->name('admin.tips-kerja.createForm');
        Route::get('/tips/kerja/{id}/edit', 'edit')->name('admin.tips-kerja.edit');
        Route::put('/tips/kerja/{id}', 'update')->name('admin.tips-kerja.update');
        Route::delete('/tips/kerja/{id}', 'destroy_single')->name('admin.tips-kerja.destroy.single');
        Route::put('/tips/kerja/{id}/toggle-status', 'toggleStatus')->name('admin.tips-kerja.toggleStatus');
        Route::put('/update/status/', 'update_status')->name('admin.tips-kerja.update.status');
        Route::delete('/delete', 'destroy')->name('admin.tips-kerja.destroy');
    });

    // EVENT CONTROLLER
    Route::controller(EventController::class)->group(function () {
        Route::get('/event', 'index')->name('admin.eventform');
        Route::post('/event/store', 'store_event')->name('admin.event.store');
        Route::get('/event/create', 'createForm')->name('admin.event.createForm');
        Route::put('/update/event/{event}', 'update_event')->name('admin.event.update');
        Route::get('/event/{event}', 'detail_event')->name('admin.detail.event');
        Route::get('/event/{event}/edit', 'edit_event')->name('admin.edit.event');
        Route::delete('/delete/event/{event}', 'destroy_event')->name('admin.event.destroy');
        Route::put('/events/status/{event}', 'updateStatus')->name('admin.event.updateStatus');
    });

    // LOWONGAN PERUSAHAAN CONTROLLER
    Route::controller(LowonganPerusahaanController::class)->group(function () {
        Route::post('/lowongan/{id}/rekomendasi', 'toggleRekomendasi')->name('admin.lowongan.toggleRekomendasi');
    });

    // Verifikasi Perusahaan
    Route::controller(VerifikasiPerusahaanController::class)->group(function () {
        Route::post('/perusahaan/approve/{id}', 'approve')->name('admin.perusahaan.approve');
        Route::post('/perusahaan/reject/{id}', 'reject')->name('admin.perusahaan.reject');
    });

    // MODERASI & MANAJEMEN IKLAN CONTROLLER
    Route::controller(IklanAdminController::class)->group(function () {
        Route::get('/iklan', 'index')->name('admin.iklan.index');
        Route::get('/iklan/pasang', 'create')->name('admin.iklan.create');
        Route::post('/iklan/pasang', 'store')->name('admin.iklan.store');
        Route::get('/iklan/{id}/edit', 'edit')->name('admin.iklan.edit');
        Route::put('/iklan/{id}', 'update')->name('admin.iklan.update');
        Route::post('/iklan/{id}/approve', 'approve')->name('admin.iklan.approve');
        Route::post('/iklan/{id}/reject', 'reject')->name('admin.iklan.reject');
        Route::post('/iklan/{id}/perpanjang', 'perpanjang')->name('admin.iklan.perpanjang');
        Route::delete('/iklan/{id}', 'destroy')->name('admin.iklan.destroy');
    });

    // LINK & HEADER (SOCIAL LINK) CONTROLLER
    Route::controller(SocialLinkController::class)->group(function () {
        Route::get('/social-links', 'indexAdmin')->name('admin.social.index');
        Route::post('/social-links', 'update')->name('admin.social.update');
        Route::get('/header-image', 'headerImageIndexAdmin')->name('admin.header.image');
        Route::put('/header-image/{nama}', 'headerImageUpdate')->name('admin.header.update');
        Route::delete('/header-image/{nama}', 'headerImageDestroy')->name('admin.header.destroy');
    });

    Route::controller(ModerasiReportController::class)->group(function () {
        Route::get('/reports', 'index')->name('admin.reports.index');
        Route::get('/reports/{report}', 'show')->name('admin.reports.show');
        Route::post('/reports/{report}/review', 'review')->name('admin.reports.review');
    });

    // LAYANAN BANTUAN & KELUHAN
    Route::controller(BantuanChatController::class)->group(function () {
        Route::get('/bantuan', 'adminIndex')->name('admin.bantuan.index');
        Route::get('/bantuan/{id}', 'adminShow')->name('admin.bantuan.show');
        Route::post('/bantuan/{id}/reply', 'adminReply')->name('admin.bantuan.reply');
        Route::post('/bantuan/{id}/status', 'adminToggleStatus')->name('admin.bantuan.status');
    });
});

