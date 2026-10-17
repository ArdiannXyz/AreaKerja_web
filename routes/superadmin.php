<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BantuanChatController;
use App\Http\Controllers\EmailSubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IklanAdminController;
use App\Http\Controllers\LowonganPerusahaanController;
use App\Http\Controllers\ManajemenLowonganController;
use App\Http\Controllers\ModerasiReportController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\PengalamanOrgController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TalentHunterController;
use App\Http\Controllers\TipsKerjaController;
use App\Http\Controllers\VerifikasiPerusahaanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/logout/superadmin', 'logout_superadmin')->name('logout_superadmin');
});

Route::prefix('super_admin')->middleware('auth', 'role:super_admin', 'CheckUserStatus')->group(function () {

    Route::controller(SuperAdminController::class)->group(function () {
        // dashboard & profile
        Route::get('/dashboard', 'index')->name('superadmin.dashboard');
        Route::get('/pengaturan', 'pengaturan')->name('superadmin.pengaturan');
        Route::get('/profile', 'profile_superadmin')->name('superadmin.profile');
        Route::get('/edit/profile', 'edit_profile')->name('superadmin.edit.profile');
        Route::put('/update/profile/{superadmin:id}', 'update_profile_superadmin')->name('superadmin.update.profile');
        Route::delete('/delete/profile/{superadmin:id}', 'destroy_profile')->name('superadmin.destroy.profile');

        // freeze akun
        Route::get('/freeze', 'freezeForm')->name('superadmin.freeze');
        Route::get('/freeze/detail/{user:id}', 'detail_freeze')->name('superadmin.detail.freeze');
        Route::put('/freeze/ban/{user:id}', 'ban')->name('superadmin.ban.freeze');
        Route::put('/freeze/unban/{user:id}', 'unban')->name('superadmin.unban.freeze');
        Route::delete('/delete/akun/{user:id}', 'delete_akun')->name('superadmin.delete.akun');

        // Pelamar
        Route::get('/pelamar', 'pelamarhal')->name('superadmin.pelamar');
        Route::get('/pelamar/tambah/{kategori}', 'createKategori')->where('kategori', '(kandidat|non_kandidat|calon_kandidat)')->name('superadmin.pelamar.create');
        Route::post('/pelamar/store', 'storeUser')->name('superadmin.pelamar.store');
        Route::get('/pelamar/edit/{kategori}/{id}', 'editUser')->where('kategori', '(non_kandidat|calon_kandidat|kandidat)')->name('superadmin.pelamar.edit');
        Route::put('/pelamar/update/{id}', 'updateUser')->name('superadmin.pelamar.update');
        Route::delete('/pelamar/{id}', 'destroyUser')->name('superadmin.pelamar.destroy');

        // kandidat & non-kandidat
        Route::get('/kandidat/{pelamar}', 'detail_kandidat')->name('superadmin.detail.kandidat');
        Route::get('/non-kandidat/{pelamar}', 'detail_non_kandidat')->name('superadmin.detail.non.kandidat');
        Route::get('/non-kandidat/{pelamar}/edit', 'edit_non_kandidat')->name('superadmin.edit.non.kandidat');

        // calon kandidat
        Route::get('/calon-kandidat/{id}', 'detailCalonKandidat')->name('superadmin.calon.detail');
        Route::post('/calon-kandidat/{id}/update', 'updateTraining')->name('superadmin.calon.update');
        Route::post('/calon-kandidat/{id}/lulus', 'lulus')->name('superadmin.calon.lulus');
        Route::post('/calon-kandidat/{id}/gugur', 'gugur')->name('superadmin.calon.gugur');

        // crud admin
        Route::get('/add/user', 'role')->name('superadmin.add.user');
        Route::get('/add/user/createForm', 'createForm')->name('superadmin.add.user.createForm');
        Route::post('/add/user/store', 'store')->name('superadmin.add.user.store');
        Route::get('/edit/user/{id}', 'edit')->name('superadmin.edit.user');
        Route::put('/update/user/{id}', 'update')->name('superadmin.update.user');
        Route::get('/detail/user/{id}', 'detail')->name('superadmin.detail.user');
        Route::delete('/delete/user/{id}', 'hapus')->name('superadmin.destroy.user');

        // Pengaturan
        Route::post('/ganti-password', 'updatePassword')->name('superadmin.password.update');

        // perusahaan
        Route::get('/perusahaan', 'halPerusahaan')->name('superadmin.perusahaan');
        Route::get('/perusahaan/{id}', 'detailPerusahaan')->name('superadmin.perusahaan.detail');
        Route::get('/lowongan/{perusahaan}/{lowongan}', 'detailLowongan')->name('superadmin.lowongan.detail');

        // paket harga
        Route::get('/paket/harga', 'halFinance')->name('superadmin.paket-harga');
        Route::get('/paket/harga/edit/koin', 'edit_koin')->name('superadmin.paket-harga.edit-koin');
        Route::put('/update/harga/koin', 'update_koin')->name('superadmin.paket-harga.update-koin');
        Route::get('/paket/harga/edit/harga', 'edit_pembayaran')->name('superadmin.paket-harga.edit-pembayaran');
        Route::put('/update/harga/harga', 'update_pembayaran')->name('superadmin.paket-harga.update-pembayaran');

        // Detail Laporan Keuangan
        Route::get('/laporan/detail/{tanggal}', 'detail_laporan')->name('superadmin.laporan.detail');
        Route::get('/laporan/{tanggal}/unduh', 'unduh_laporan_harian')->name('superadmin.laporan.unduh');

        // Panggilan
        Route::get('/panggilan', 'panggilan')->name('superadmin.panggilan');
        Route::get('/panggilan/{perusahaan_id}/list', 'listPekerja')->name('superadmin.panggilan.list');

        // recruitment
        Route::get('/recruitment/perusahaan', 'recruitmentPerusahaan')->name('superadmin.recruitment.perusahaan');
        Route::get('/recruitment/{id}', 'recruitment')->name('superadmin.recruitment');
        Route::get('/recruitment/{id}/detail', 'detailRecruitment')->name('superadmin.recruitment.detail');
        Route::delete('/recruitment/{id}/hapus', 'destroyRecruitment')->name('superadmin.recruitment.destroy');

        // talent hunter
        Route::get('/talent/hunter', 'talentHunterForm')->name('superadmin.talent-hunter');
        Route::get('/talent/hunter/{id}', 'detailDataTalentHunter')->name('superadmin.talent-hunter.detail');
    });

    // TIPS KERJA CONTROLLER
    Route::controller(TipsKerjaController::class)->group(function () {
        Route::get('/tips/kerja', 'index_superadmin')->name('superadmin.tips-kerja');
        Route::post('/tips/kerja/', 'store_tips_kerja_superadmin')->name('superadmin.tips-kerja.store');
        Route::get('/tips/kerja/create', 'tips_kerja_buat_post_superadmin')->name('superadmin.tips-kerja.createForm');
        Route::put('/update/status/', 'update_status_superadmin')->name('superadmin.tips-kerja.update.status');
        Route::delete('/delete', 'destroy_superadmin')->name('superadmin.tips-kerja.destroy');
    });

    // EVENT CONTROLLER
    Route::controller(EventController::class)->group(function () {
        Route::get('/event', 'index')->name('superadmin.eventform');
        Route::post('/event/store', 'store_event')->name('superadmin.event.store');
        Route::get('/event/create', 'createForm')->name('superadmin.event.createForm');
        Route::put('/update/event/{event}', 'update_event')->name('superadmin.event.update');
        Route::get('/event/{event}', 'detail_event')->name('superadmin.detail.event');
        Route::get('/event/{event}/edit', 'edit_event')->name('superadmin.edit.event');
        Route::delete('/delete/event/{event}', 'destroy_event')->name('superadmin.event.destroy');
        Route::put('/events/status/{event}', 'updateStatus')->name('superadmin.event.updateStatus');
    });

    // ADMIN CONTROLLER
    Route::controller(AdminController::class)->group(function () {
        Route::get('/get-kota/{provinsi_id}', 'getKota')->name('superadmin.get.kota');
        Route::get('/get-kecamatan/{kota_id}', 'getKecamatan')->name('superadmin.get.kecamatan');
    });

    // LOWONGAN CONTROLLER
    Route::controller(LowonganPerusahaanController::class)->group(function () {
        Route::get('/perusahaan/{id}/createform/lowongan', 'createFormSuper')->name('superadmin.lowongan.create.form');
        Route::post('/perusahaan/{id}/buat/lowongan', 'storeSuper')->name('superadmin.lowongan.saya.store');
        Route::get('/edit/lowongan/{lowongan}', 'editSuper')->name('superadmin.lowongan.edit.form');
        Route::put('/update/lowongan/{lowongan}', 'updateSuper')->name('superadmin.lowongan.update');
        Route::delete('/lowongan/{lowongan}', 'destroySuper')->name('superadmin.lowongan.destroy');
        Route::post('/lowongan/{id}/rekomendasi', 'toggleRekomendasi')->name('superadmin.lowongan.toggleRekomendasi');
    });

    // PROFILE CONTROLLER
    Route::controller(ProfileController::class)->group(function () {
        Route::post('/create/alamat', 'store_alamatSuper')->name('superadmin.alamat.store')->middleware('auth');
        Route::get('/edit/alamat/{alamatpelamar:id}', 'edit_alamatSuper')->name('superadmin.alamat.edit')->middleware('auth');
        Route::put('/update/alamat/{alamatpelamar?}', 'update_alamatSuper')->name('superadmin.alamat.update')->middleware('auth');
        Route::delete('/delete/alamat/{alamatpelamar:id}', 'destroy_alamatSuper')->name('superadmin.alamat.destroy')->middleware('auth');
    });

    // PELAMAR CONTROLLER
    Route::controller(PelamarController::class)->group(function () {
        Route::post('/create/pendidikan', 'storependidikanSuper')->name('superadmin.pendidikan.store')->middleware('auth');
        Route::get('/edit/pendidikan/{riwayatpendidikan:id}', 'editpendidikanSuper')->name('superadmin.pendidikan.edit')->middleware('auth');
        Route::put('/update/pendidikan/{riwayatpendidikan?}', 'updatependidikanSuper')->name('superadmin.pendidikan.update');
        Route::delete('/delete/pendidikan/{riwayatpendidikan:id}', 'destroypendidikanSuper')->name('superadmin.pendidikan.destroy')->middleware('auth');
    });

    // PENGALAMAN ORGANISASI CONTROLLER
    Route::controller(PengalamanOrgController::class)->group(function () {
        Route::post('/create/organisasi', 'storeSuper')->name('superadmin.organisasi.store')->middleware('auth');
        Route::get('/edit/organisasi/{organisasi:id}', 'editSuper')->name('superadmin.organisasi.edit')->middleware('auth');
        Route::put('/update/organisasi/{organisasi?}', 'updateSuper')->name('superadmin.organisasi.update')->middleware('auth');
        Route::delete('/delete/organisasi/{organisasi:id}', 'destroySuper')->name('superadmin.organisasi.destroy')->middleware('auth');
    });

    // PENGALAMAN KERJA CONTROLLER
    Route::controller(PengalamanKerjaController::class)->group(function () {
        Route::post('/create/kerja', 'storeSuper')->name('superadmin.kerja.store')->middleware('auth');
        Route::get('/edit/kerja/{kerja:id}', 'editSuper')->name('superadmin.kerja.edit')->middleware('auth');
        Route::put('/update/kerja/{kerja?}', 'updateSuper')->name('superadmin.kerja.update')->middleware('auth');
        Route::delete('/delete/kerja/{kerja:id}', 'destroySuper')->name('superadmin.kerja.destroy')->middleware('auth');
    });

    // SKILL CONTROLLER
    Route::controller(SkillController::class)->group(function () {
        Route::post('/create/skill', 'storeSuper')->name('superadmin.skill.store')->middleware('auth');
        Route::get('/edit/skill/{skill:id}', 'editSuper')->name('superadmin.skill.edit')->middleware('auth');
        Route::put('/update/skill/{skill?}', 'updateSuper')->name('superadmin.skill.update')->middleware('auth');
        Route::delete('/delete/skill/{skill:id}', 'destroySuper')->name('superadmin.skill.destroy')->middleware('auth');
    });

    // MANAJEMEN LOWONGAN CONTROLLER
    Route::controller(ManajemenLowonganController::class)->group(function () {
        Route::get('/manajemen/lowongan/gold', 'gold')->name('superadmin.manajemen.lowongan.gold')->middleware('auth');
        Route::get('/manajemen/lowongan/silver', 'silver')->name('superadmin.manajemen.lowongan.silver')->middleware('auth');
        Route::get('/manajemen/lowongan/bronze', 'bronze')->name('superadmin.manajemen.lowongan.bronze')->middleware('auth');

        Route::post('/manajemen/lowongan/gold/update', 'updateGold')->name('superadmin.manajemen.lowongan.gold.update')->middleware('auth');
        Route::post('/manajemen/lowongan/silver/update', 'updateSilver')->name('superadmin.manajemen.lowongan.silver.update')->middleware('auth');
        Route::post('/manajemen/lowongan/bronze/update', 'updateBronze')->name('superadmin.manajemen.lowongan.bronze.update')->middleware('auth');
    });

    // SOCIAL LINK CONTROLLER
    Route::controller(SocialLinkController::class)->group(function () {
        Route::get('/social-links', 'index')->name('superadmin.social.index')->middleware('auth');
        Route::post('/social-links', 'update')->name('superadmin.social.update')->middleware('auth');
        Route::get('/header-image', 'headerImageIndex')->name('superadmin.header.image')->middleware('auth');
        Route::put('/header-image/{nama}', 'headerImageUpdate')->name('superadmin.header.update')->middleware('auth');
        Route::delete('/header-image/{nama}', 'headerImageDestroy')->name('superadmin.header.destroy')->middleware('auth');
    });

    // TALENT HUNTER CONTROLLER
    Route::controller(TalentHunterController::class)->group(function () {
        Route::get('/talent-hunter/{id}/edit', 'editTalentHunter')->name('superadmin.talent-hunter.edit')->middleware('auth');
        Route::put('/talent-hunter/{id}', 'update')->name('superadmin.talent-hunter.update')->middleware('auth');
    });

    // EMAIL SUBS
    Route::controller(EmailSubController::class)->group(function () {
        Route::get('/email-subs', 'halEmail')->name('superadmin.email-subs.index');
        Route::get('/email-subscribers/pdf', 'downloadPdf')->name('superadmin.email-subscribers.pdf');
        Route::delete('/email-subs/bulk-delete', 'bulkDelete')->name('superadmin.email-subs.bulk-delete');
    });

    // Verifikasi Perusahaan
    Route::controller(VerifikasiPerusahaanController::class)->group(function () {
        Route::post('/perusahaan/approve/{id}', 'approveSuper')->name('superadmin.perusahaan.approve');
        Route::post('/perusahaan/reject/{id}', 'rejectSuper')->name('superadmin.perusahaan.reject');
    });

    // MODERASI & MANAJEMEN IKLAN CONTROLLER (SUPER ADMIN)
    Route::controller(IklanAdminController::class)->group(function () {
        Route::get('/iklan', 'indexSuper')->name('superadmin.iklan.index');
        Route::get('/iklan/pasang', 'createSuper')->name('superadmin.iklan.create');
        Route::post('/iklan/pasang', 'storeSuper')->name('superadmin.iklan.store');
        Route::get('/iklan/{id}/edit', 'editSuper')->name('superadmin.iklan.edit');
        Route::put('/iklan/{id}', 'updateSuper')->name('superadmin.iklan.update');
        Route::post('/iklan/{id}/approve', 'approveSuper')->name('superadmin.iklan.approve');
        Route::post('/iklan/{id}/reject', 'rejectSuper')->name('superadmin.iklan.reject');
        Route::post('/iklan/{id}/perpanjang', 'perpanjangSuper')->name('superadmin.iklan.perpanjang');
        Route::delete('/iklan/{id}', 'destroySuper')->name('superadmin.iklan.destroy');
    });

    Route::controller(ModerasiReportController::class)->group(function () {
        Route::get('/reports', 'index')->name('superadmin.reports.index');
        Route::get('/reports/{report}', 'show')->name('superadmin.reports.show');
        Route::post('/reports/{report}/review', 'review')->name('superadmin.reports.review');
    });

    // LAYANAN BANTUAN & KELUHAN (SUPER ADMIN)
    Route::controller(BantuanChatController::class)->group(function () {
        Route::get('/bantuan', 'adminIndex')->name('superadmin.bantuan.index');
        Route::get('/bantuan/{id}', 'adminShow')->name('superadmin.bantuan.show');
        Route::post('/bantuan/{id}/reply', 'adminReply')->name('superadmin.bantuan.reply');
        Route::post('/bantuan/{id}/status', 'adminToggleStatus')->name('superadmin.bantuan.status');
    });
});

