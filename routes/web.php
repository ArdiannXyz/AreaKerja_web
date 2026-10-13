<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\EmailSubController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IklanPerusahaanController;
use App\Http\Controllers\LupaPasswordController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareLowonganController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Public, Auth, & Common Entrypoints)
|--------------------------------------------------------------------------
*/

// TRACKING KLIK IKLAN HOMEPAGE
Route::get('/iklan/{id}/click', [IklanPerusahaanController::class, 'handleClick'])->name('iklan.click');

// OAUTH SOCIAL AUTHENTICATION (Google, Facebook, GitHub)
Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/auth/{provider}/redirect', 'redirect')->name('social.redirect');
    Route::get('/auth/{provider}/callback', 'callback')->name('social.callback');
    Route::get('/auth/social/pilih-role', 'pilihRole')->name('social.pilih-role');
    Route::match(['get', 'post'], '/auth/social/simpan-role', 'simpanRole')->name('social.simpan-role');
});

// UPLOAD TINYMCE
Route::controller(UploadController::class)->group(function () {
    Route::post('/tinymce-upload', 'tinymceUpload')->name('tinymce.upload');
    Route::get('/tinymce-mention', 'tinymceMention')->name('tinymce.mention');
});

// SHARE LOWONGAN & TIPS
Route::controller(ShareLowonganController::class)->group(function () {
    Route::get('/share/{platform}/{companySlug}/{jobSlug}', 'share')->name('lowongan.share');
    Route::get('/tips/share/{platform}/{tips}', 'sharetips')->name('tips.share');
});

// PELAMAR SEARCH & PUBLIC ACTIONS
Route::controller(PelamarController::class)->group(function () {
    Route::get('/search', 'searchLowongan')->name('lowongan.search');
    Route::post('/pelamar/reset-riwayat', 'resetRiwayat')->name('pelamar.resetRiwayat');
});

// REGION DROPDOWNS API
Route::get('/get-kota/{provinsi_id}', [AdminController::class, 'getKota'])->name('public.get.kota');
Route::get('/get-kecamatan/{kota_id}', [AdminController::class, 'getKecamatan'])->name('public.get.kecamatan');

// CV PREVIEW & DOWNLOAD
Route::controller(CVController::class)->group(function () {
    Route::get('/cv/{pelamar:id}/download', 'downloadCv')->name('cv.download');
    Route::get('/cv/{pelamar:id}/preview', 'preview')->name('cv.preview');
});

// HALAMAN BERANDA PUBLIK
Route::get('/', [AuthController::class, 'beranda']);

// LOGIN & LOGOUT AUTH
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'login_non_user')->name('login');
    Route::post('/loginproses', 'loginproses')->name('loginproses')->middleware('throttle:60,1');
});

Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/logout', 'logout_pelamar')->name('logout_pelamar');
});

// REGISTRASI PELAMAR
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'regis_non_user')->name('register')->middleware('guest');
    Route::post('/registerproses', 'regis_proses')->name('registerproses')->middleware(['guest', 'throttle:30,1']);
});

// VERIFIKASI PASSWORD & OTP
Route::controller(LupaPasswordController::class)->group(function () {
    Route::get('/verifikasi', 'showEmailForm_pelamar')->name('verifikasi_pelamar');
    Route::post('/verifikasi', 'sendOtp')->name('password.email.pelamar')->middleware('throttle:30,1');
    Route::get('/verifikasi/otp/{token}', 'showOtpForm_pelamar')->name('password.otp.form.pelamar');
    Route::post('/verifikasi/otp', 'verifyOtp')->name('password.otp.verif.pelamar')->middleware('throttle:30,1');
    Route::get('/reset-password/{token}', 'showResetForm_pelamar')->name('password.reset.form.pelamar');
    Route::post('/reset-password/{token}', 'resetPassword')->name('password.update.pelamar')->middleware('throttle:30,1');
    Route::post('/verifikasi/otp/resend', 'resendOtp')->name('password.otp.resend.pelamar')->middleware('throttle:30,1');
});

// VERIFIKASI EMAIL
Route::controller(EmailVerificationController::class)->middleware(['auth', 'email.role'])->group(function () {
    Route::get('email/ubah', 'showChangeEmailForm')->name('email.ubah');
    Route::post('/email/send-verification', 'sendVerification')->name('email.send.verification');
});

Route::controller(EmailVerificationController::class)->group(function () {
    Route::get('/email/verify/{token}', 'verify')->name('email.verify');
});

// TIPS KERJA PUBLIK
Route::controller(PelamarController::class)->group(function () {
    Route::get('/pelamar/tips-kerja', 'tips_kerja')->name('pelamar.tips-kerja');
    Route::get('/pelamar/tips-kerja/{tips}', 'detail')->name('pelamar.tips-kerja.show');
});

// SYARAT DAN KETENTUAN
Route::get('/images/syarat-dan-ketentuan-banner.png', function () {
    $targetPath = public_path('images/syarat-ketentuan.png');
    if (file_exists($targetPath)) {
        return response()->file($targetPath);
    }
    return response()->file(public_path('images/gambarkom.jpg'));
});

Route::get('/syarat-dan-ketentuan', function () {
    return view('layouts.syarat-dan-ketentuan');
})->name('syarat.ketentuan');

// EMAIL SUBSCRIBER
Route::controller(EmailSubController::class)->group(function () {
    Route::post('/email-subscribe', 'index')->name('subscribe.email');
    Route::get('/newsletter/unsubscribe', 'unsubscribe')->name('newsletter.unsubscribe');
    Route::post('/newsletter/resubscribe', 'resubscribe')->name('newsletter.resubscribe');
});

// PELAMAR BERANDA & DETAIL LOWONGAN
Route::controller(PelamarController::class)->middleware('CheckUserStatus')->group(function () {
    Route::get('/pelamar/home', 'index')->name('beranda');
    Route::get('/pelamar/beranda', 'index')->name('pelamar.beranda');
    Route::get('/pelamar/detail-lowongan/{perusahaan}/{lowongan}', 'detail_lowongan_non_user')->name('detail.lowongan.non.user');
    Route::get('/pelamar/daftar-kandidat', 'daftar_kandidat')->name('pelamar.daftar-kandidat');
    Route::get('/lowongan-tersimpan', 'lowongansimpanform')->name('lowongan.tersimpan');
    Route::get('/lamaran-kerja', 'lamaranKerja')->name('pelamar.lamaran-kerja');
});

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('CheckUserStatus');

// EVENT PUBLIK
Route::controller(EventController::class)->group(function () {
    Route::get('/event', 'publicEventList')->name('pelamar.event.index');
    Route::get('/event/{id}', 'publicEventShow')->name('pelamar.event.show');
    Route::post('/event/{id}/daftar', 'daftarEvent')->name('pelamar.event.daftar');
});

/*
|--------------------------------------------------------------------------
| Modular Role Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/pelamar.php';
require __DIR__ . '/finance.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/superadmin.php';
require __DIR__ . '/perusahaan.php';
