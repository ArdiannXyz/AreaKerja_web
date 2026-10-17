<?php

use App\Http\Controllers\KandidatController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\PelamarLowonganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PembeliKandidatController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\PengalamanOrgController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TipsKerjaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pelamar Routes
|--------------------------------------------------------------------------
*/

Route::prefix('pelamar')->middleware('auth', 'role:pelamar', 'CheckUserStatus')->group(function () {

    // PELAMAR CONTROLLER
    Route::controller(PelamarController::class)->group(function () {
        // ganti password
        Route::post('/ganti-password', 'updatePassword')->name('pelamar.password.update');

        // riwayat pendidikan
        Route::post('/create/pendidikan', 'storependidikan')->name('pendidikan.store');
        Route::get('/edit/pendidikan/{riwayatpendidikan:id}', 'editpendidikan')->name('pendidikan.edit');
        Route::put('/update/pendidikan/{riwayatpendidikan:id}', 'updatependidikan')->name('pendidikan.update');
        Route::delete('/delete/pendidikan/{riwayatpendidikan:id}', 'destroypendidikan')->name('pendidikan.destroy');

        // simpan lowongan
        Route::post('/simpan-lowongan', 'store')->name('simpan-lowongan.store');
        Route::delete('/simpan-lowongan/{id}', 'destroy')->name('simpan-lowongan.destroy');

        // detail lowongan
        Route::get('/detail-lowongan/{slug}', 'detail_lowongan_non_userShare')->name('lowongan.show');

        // notifikasi lamaran pelamar
        Route::get('/notifikasi', 'notifikasi')->name('pelamar.notifikasi');

        // daftar kandidat
        Route::post('/kandidat/pendaftaran', 'storePendaftaran')->name('kandidat.storePendaftaran');
        Route::get('/kandidat/transaksi/{id}', 'transaksi')->name('kandidat.transaksi');
        Route::get('/kandidat/transaksi/{id}/status', 'checkStatusKandidat')->name('kandidat.transaksi.status');
        Route::post('/kandidat/{id}/upload-bukti', 'uploadBukti')->name('kandidat.catatan_cash.upload_bukti');

        // Transaksi 
        Route::get('/transaksi', 'transaksiPendaftaranKandidat')->name('transaksi.pendaftaran');
    });

    Route::controller(PelamarLowonganController::class)->group(function () {
        // lamar
        Route::post('/lamar-cepat/{lowongan}', 'storeQuick')->name('lamar.cepat');
    });

    Route::post('/lowongan/{lowongan}/report', [ReportController::class, 'store'])
        ->middleware('throttle:reports')
        ->name('pelamar.lowongan.report');

    // PROFILE CONTROLLER
    Route::controller(ProfileController::class)->group(function () {
        // profile
        Route::get('/edit/profile', 'edit')->name('profile.edit');
        Route::put('/update/profile/{pelamar:id}', 'update_profile')->name('profile.update');
        Route::delete('/delete/profile/{pelamar:id}', 'destroy_profile')->name('profile.destroy');

        // update kategori pelamar
        Route::put('/update-kategori/{id}', 'updateKategori')->name('kategori.update');

        // alamat pelamar
        Route::get('/alamat', 'alamat')->name('alamat');
        Route::get('/form/alamat', 'form_alamat')->name('form_alamat');
        Route::post('/create/alamat', 'store_alamat')->name('alamat.store');
        Route::get('/edit/alamat/{alamatpelamar:id}', 'edit_alamat')->name('alamat.edit');
        Route::put('/update/alamat/{alamatpelamar:id}', 'update_alamat')->name('alamat.update');
        Route::delete('/delete/alamat/{alamatpelamar:id}', 'destroy_alamat')->name('alamat.destroy');
    });

    // PENGALAMAN ORGANISASI CONTROLLER
    Route::controller(PengalamanOrgController::class)->group(function () {
        Route::post('/create/organisasi', 'store')->name('organisasi.store');
        Route::get('/edit/organisasi/{organisasi:id}', 'edit')->name('organisasi.edit');
        Route::put('/update/organisasi/{organisasi:id}', 'update')->name('organisasi.update');
        Route::delete('/delete/organisasi/{organisasi:id}', 'destroy')->name('organisasi.destroy');
    });

    // PENGALAMAN KERJA CONTROLLER
    Route::controller(PengalamanKerjaController::class)->group(function () {
        Route::post('/create/kerja', 'store')->name('kerja.store');
        Route::get('/edit/kerja/{kerja:id}', 'edit')->name('kerja.edit');
        Route::put('/update/kerja/{kerja:id}', 'update')->name('kerja.update');
        Route::delete('/delete/kerja/{kerja:id}', 'destroy')->name('kerja.destroy');
    });

    // SKILL CONTROLLER
    Route::controller(SkillController::class)->group(function () {
        Route::post('/create/skill', 'store')->name('skill.store');
        Route::get('/edit/skill/{skill:id}', 'edit')->name('skill.edit');
        Route::put('/update/skill/{skill:id}', 'update')->name('skill.update');
        Route::delete('/delete/skill/{skill:id}', 'destroy')->name('skill.destroy');
    });

    // KANDIDAT CONTROLLER
    Route::controller(KandidatController::class)->group(function () {
        Route::get('/calon/kandidat/pelatihan', 'rekrutHalKosong')->name('pelamar.calon-kandidat.pelatihan')->middleware('cekKategori:calon kandidat');
    });

    // PEMBELIAN KANDIDAT
    Route::controller(PembeliKandidatController::class)->group(function () {
        Route::get('/tawaran', 'tawaran')->name('pelamar.tawaran');
        Route::get('/kandidat/tawaran/{perusahaan}/{lowongan}', 'detailTawaran')->name('kandidat.detailTawaran');
        Route::post('/pembeli_kandidat/{id}/status', 'updateStatus')->name('kandidat.updateStatus');
    });
});

// PELAMAR UMUM / NOTIFIKASI
Route::controller(PelamarController::class)->group(function () {
    Route::post('/notifikasi/baca/semua', 'bacaSemua')->name('notifikasi.bacaSemua');
    Route::post('/notifikasi/baca/{id}', 'baca')->name('notifikasi.baca');
    Route::delete('/notifikasi/hapus/{id}', 'hapus')->name('notifikasi.hapus');
    Route::delete('/notifikasi/hapus-semua', 'hapusSemua')->name('notifikasi.hapusSemua');
    Route::delete('/notifikasi/hapus-semua-baca', 'hapusSemuaBaca')->name('notifikasi.hapusSemuaBaca');

    Route::get('/lowongan', 'pasangLowongan')->name('pelamar.pasangLowongan');
    Route::get('/talent-hunter', 'talentHunter')->name('pelamar.talentHunter');
    Route::get('/bantuan', 'bantuan')->name('pelamar.bantuan');
    Route::get('/syarat/ketentuan', 'syaratKetentuan')->name('pelamar.syaratKetentuan');
});

// TIPS KERJA CONTROLLER
Route::controller(TipsKerjaController::class)->group(function () {
    Route::get('/tips1', 'tips_kerja_tips1')->name('tips-kerja.tips1');
});

