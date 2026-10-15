<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanCashController;
use App\Http\Controllers\IklanPerusahaanController;
use App\Http\Controllers\LowonganPerusahaanController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\PembeliKandidatController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\TalentHunterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Perusahaan Routes
|--------------------------------------------------------------------------
*/

Route::controller(LowonganPerusahaanController::class)->group(function () {
    Route::get('/perusahaan/{perusahaan:slug}/lowongan/{lowongan:slug}', 'show')
        ->name('lowongan.detail')
        ->middleware('auth', 'CheckUserStatus');
});

Route::prefix('perusahaan')->middleware('auth', 'role:perusahaan', 'CheckUserStatus')->group(function () {

    // AUTH CONTROLLER
    Route::controller(AuthController::class)->group(function () {
        Route::get('/dashboard', 'beranda_perusahaan')->name('perusahaan.dashboard');
    });

    // PERUSAHAAN CONTROLLER (GENERAL)
    Route::controller(PerusahaanController::class)->group(function () {
        Route::get('/profile', 'profile_perusahaan')->name('profile.perusahaan');
        Route::get('/edit/profile', 'edit_profile')->name('profile.edit.perusahaan');
        Route::put('/update/profile/{perusahaan:id}', 'update_profile_perusahaan')->name('profile.update.perusahaan');
        Route::delete('/delete/profile/{perusahaan:id}', 'destroy_profile')->name('profile.destroy.perusahaan');

        Route::get('/alamat', 'alamat_perusahaan')->name('alamat.perusahaan');
        Route::get('/form/alamat', 'form_alamat')->name('form.alamat.perusahaan');
        Route::post('/alamat-perusahaan/{id}/utama', 'setUtama')->name('alamat-perusahaan.setUtama');
        Route::post('/create/alamat', 'store_alamat')->name('alamat.store.perusahaan');
        Route::get('/edit/alamat/{id}', 'edit_alamat')->name('alamat.edit.perusahaan');
        Route::put('/update/alamat/{id}', 'update_alamat')->name('alamat.update.perusahaan');
        Route::delete('/delete/alamat/{id}', 'destroy_alamat')->name('alamat.destroy.perusahaan');

        Route::get('pelamar/{lowongan:slug}', 'pelamar')->name('perusahaan.pelamar');
        Route::get('/pengaturan', 'pengaturanForm')->name('perusahaan.pengaturan');
        Route::get('/kandidat/ak', 'kandidat_ak')->name('perusahaan.kandidat.ak');
        Route::get('/event', 'event')->name('perusahaan.event.index');
        Route::get('/gabung/event/{id}', 'detail')->name('perusahaan.event.show');
        Route::get('/berlangganan', 'halLangganan')->name('perusahaan.berlangganan');

        // Iklan Perusahaan
        Route::controller(IklanPerusahaanController::class)->group(function () {
            Route::get('/iklan', 'index')->name('perusahaan.iklan.index');
            Route::get('/iklan/pasang', 'create')->name('perusahaan.iklan.create');
            Route::post('/iklan/pasang', 'store')->name('perusahaan.iklan.store');
        });

        Route::get('/recruitment/kandidat-saya', 'kandidatSaya')->name('perusahaan.kandidat.saya');
        Route::get('/data/pekerja', 'halDaftarPekerja')->name('perusahaan.data.pekerja');
        Route::get('/data/pekerja-bermasalah', 'listPekerjaBermasalah')->name('perusahaan.data.pekerja-bermasalah');
        Route::get('/cari-nama-pekerja', 'halCariNamaPekerja')->name('perusahaan.cari.nama.pekerja');
        Route::get('/laporan-harian', 'halLaporanHarianPekerja')->name('perusahaan.laporan.harian');
    });

    // PERUSAHAAN CONTROLLER (VERIFIED ONLY)
    Route::controller(PerusahaanController::class)->middleware('perusahaan.verified')->group(function () {
        Route::post('/ganti-password', 'updatePassword')->name('password.update');
        Route::post('/berlangganan', 'storeLangganan')->name('berlangganan.store');
        Route::post('/send-email', 'kirimEmail')->name('send.email')->middleware('auth');
        Route::delete('/recruitment/{id}/hapus', 'destroyRecruitmentPerusahaan')->name('perusahaan.destroy.kandidat');
        Route::post('/diskon-fitur', 'DiskonFitur')->name('diskon.fitur');
        Route::post('/pekerja-bermasalah/wa', 'PekerjaBermasalahWA')->name('perusahaan.pekerja.bermasalah.wa');
        Route::post('/laporan-harian/pekerja/wa', 'LaporanHarianPekerjaWA')->name('perusahaan.laporan.harian.pekerja.wa');
        Route::post('/cari-nama-pekerja/wa', 'CariPekerjaWA')->name('perusahaan.cari.nama.pekerja.wa');
    });

    // CATATAN CASH CONTROLLER (VERIFIED ONLY)
    Route::controller(CatatanCashController::class)->middleware('perusahaan.verified')->group(function () {
        Route::post('/topup/store', 'store')->name('catatan_cash.store');
        Route::get('/topup/{id}', 'show')->name('catatan_cash.show');
        Route::post('/topup/{id}/upload-bukti', 'uploadBukti')->name('catatan_cash.upload_bukti');
    });

    // LOWONGAN CONTROLLER
    Route::controller(LowonganPerusahaanController::class)->group(function () {
        Route::get('/lowongan', 'index')->name('lowongan.saya.perusahaan');
        Route::get('/paket/form', 'paketform')->name('paket.form');
    });

    // LOWONGAN CONTROLLER (VERIFIED ONLY)
    Route::controller(LowonganPerusahaanController::class)->middleware('perusahaan.verified')->group(function () {
        Route::get('/createform/lowongan', 'createForm')->name('lowongan.create.form');
        Route::post('/buat/lowongan', 'store')->name('lowongan.saya.store');
        Route::get('/edit/lowongan/{perusahaan}/{lowongan}', 'edit')->name('lowongan.edit.form');
        Route::put('/update/lowongan/{lowongan:id}', 'update')->name('lowongan.update');
        Route::delete('/lowongan/{lowongan:id}', 'destroy')->name('lowongan.destroy');
        Route::put('/lowongan/{id}/toggle-status', 'toggleStatus')->name('lowongan.toggleStatus');
        Route::post('/paket/beli', 'beliPaket')->name('paket.beli');
        Route::post('/lowongan/{lowongan}/publish', 'publish')->name('lowongan.publish');
        Route::post('/boost-lowongan', 'boost')->name('boost.lowongan');
    });

    // PELAMAR CONTROLLER (VERIFIED ONLY)
    Route::controller(PelamarController::class)->middleware('perusahaan.verified')->group(function () {
        Route::get('/pelamar/{pelamarlowongan}/konfirmasihal', 'konfirmasi_hal')->name('pelamar.konfirmasi');
        Route::post('/pelamar/{pelamarlowongan}/konfirmasi', 'konfirmasi_simpan')->name('pelamar.konfirmasi.simpan');
        Route::post('/pelamar/{pelamarlowongan}/kirim', 'kirim')->name('pelamar.konfirmasi.kirim');
        Route::post('/pelamar/{pelamarlowongan}/tolak', 'tolak')->name('pelamar.tolak');
        Route::get('/pelamar/{pelamarlowongan}/detail', 'preview')->name('pelamar.detail');
    });

    // PEMBELI KANDIDAT CONTROLLER (VERIFIED ONLY)
    Route::controller(PembeliKandidatController::class)->middleware('perusahaan.verified')->group(function () {
        Route::post('/kandidat/beli', 'beli')->name('kandidat.beli');
    });

    // TALENT HUNTER CONTROLLER
    Route::controller(TalentHunterController::class)->group(function () {
        Route::get('/talent-hunter', 'index')->name('talent-hunter.index');
    });

    // TALENT HUNTER CONTROLLER (VERIFIED ONLY)
    Route::controller(TalentHunterController::class)->middleware('perusahaan.verified')->group(function () {
        Route::get('/talent-hunter/harga', 'getHarga')->name('talent-hunter.harga');
        Route::post('/talent-hunter/beli', 'beli')->name('talent-hunter.beli');
        Route::post('/talent-hunter/store', 'store')->name('talent-hunter.store');
    });
});

// UMUM / AUTH PERUSAHAAN
Route::controller(PerusahaanController::class)->group(function () {
    Route::get('/get-kota/{provinsi_id}', 'getKota')->name('get.kota')->middleware('auth');
    Route::get('/get-kecamatan/{kota_id}', 'getKecamatan')->name('get.kecamatan')->middleware('auth');
});

Route::controller(AuthController::class)->middleware('auth')->group(function () {
    Route::post('/logout/perusahaan', 'logout_perusahaan')->name('logout_perusahaan');
});

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::post('/registerproses_perusahaan', 'regis_proses_perusahaan')->name('registerproses_perusahaan')->middleware('throttle:30,1');
});

