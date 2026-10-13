<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanCashController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Finance Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/logout/finance', 'logout_finance')->name('logout_finance');
});

Route::prefix('finance')->middleware('auth', 'role:finance', 'CheckUserStatus')->group(function () {

    // AUTH CONTROLLER
    Route::controller(AuthController::class)->group(function () {
        Route::get('/dashboard', 'beranda_finance')->name('finance.dashboard');
    });

    // CATATAN CASH CONTROLLER
    Route::controller(CatatanCashController::class)->group(function () {
        Route::post('/topup/{id}/update-status', 'updateStatus')->name('catatan_cash.update_status');
    });

    // PAKET HARGA
    Route::controller(HargaController::class)->group(function () {
        Route::get('/paket/harga', 'index')->name('finance.paket-harga');
        Route::get('/paketharga/edit/koin', 'edit_koin')->name('finance.paket-harga.edit-koin');
        Route::put('/update/harga/koin', 'update_koin')->name('finance.paket-harga.update-koin');
        Route::get('/paketharga/edit/harga', 'edit_pembayaran')->name('finance.paket-harga.edit-pembayaran');
        Route::put('/update/harga/harga', 'update_pembayaran')->name('finance.paket-harga.update-pembayaran');
    });

    // FINANCE CONTROLLER
    Route::controller(FinanceController::class)->group(function () {
        Route::get('/profile', 'profile_finance')->name('finance.profile');
        Route::get('/edit/profile', 'edit_profile_finance')->name('finance.edit.profile');
        Route::put('/update/profile/{user}', 'update_profile_finance')->name('finance.update.profile');
        Route::delete('/delete/profile/{user}', 'destroy_profile_finance')->name('finance.destroy.profile');

        Route::get('/get-kota/{provinsi_id}', 'getKotaFinance')->name('finance.get.kota');
        Route::get('/get-kecamatan/{kota_id}', 'getKecamatanFinance')->name('finance.get.kecamatan');

        Route::get('/omset', 'omset_perusahaan')->name('finance.omset');
        Route::get('/finance/omset/unduh', 'unduh_omset')->name('finance.omset.unduh');

        Route::get('/laporan/transaksi', 'laporan')->name('finance.catatan');
        Route::get('/laporan', 'laporan_transaksi')->name('finance.laporan');
        Route::get('/finance/laporan/detail/{tanggal}', 'detail_laporan')->name('finance.laporan.detail');
        Route::get('/finance/laporan/{tanggal}/unduh', 'unduh_laporan_harian')->name('finance.laporan.unduh');

        Route::get('/detail', 'hal_detail')->name('finance.detail.catatan.koin');
        Route::get('/detail/{id}', 'detail')->name('finance.detail.id');
        Route::post('/verifikasi/{id}', 'verifikasi')->name('finance.verifikasi');

        Route::get('/page/unduh/omset', 'pageUnduhOmset')->name('finance.omset.unduh.pdf');
    });

    // METODE PEMBAYARAN / REKENING BANK
    Route::controller(PembayaranController::class)->group(function () {
        Route::get('/bank', 'index')->name('finance.bank.index');
        Route::post('/bank', 'store')->name('finance.bank.store');
        Route::put('/bank/{id}', 'update')->name('finance.bank.update');
        Route::delete('/bank/{id}', 'destroy')->name('finance.bank.destroy');
    });
});

