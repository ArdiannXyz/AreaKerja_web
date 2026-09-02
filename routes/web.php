<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlamatPelamarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanCashController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\EmailSubController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\LowonganPerusahaanController;
use App\Http\Controllers\LupaPasswordController;
use App\Http\Controllers\ManajemenLowonganController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\PelamarLowonganController;
use App\Http\Controllers\PembeliKandidatController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\PengalamanOrgController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareLowonganController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TalentHunterController;
use App\Http\Controllers\TipsKerjaController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UploadController;

// OAUTH SOCIAL AUTHENTICATION (Google, Facebook, LinkedIn)
Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/auth/{provider}/redirect', 'redirect')->name('social.redirect');
    Route::get('/auth/{provider}/callback', 'callback')->name('social.callback');
});

use App\Http\Controllers\VerifikasiPerusahaanController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//UPLOAD TINYMCE
Route::controller(UploadController::class)->group(function () {
    Route::post('/tinymce-upload', 'tinymceUpload')->name('tinymce.upload');
    Route::get('/tinymce-mention', 'tinymceMention')->name('tinymce.mention');
});


//SHARE LOWONGAN
Route::controller(ShareLowonganController::class)->group(function () {
    Route::get('/share/{platform}/{companySlug}/{jobSlug}', 'share')
        ->name('lowongan.share');
    Route::get('/tips/share/{platform}/{tips}', 'sharetips')->name('tips.share');
});


//PELAMAR CONTROLLER
Route::controller(PelamarController::class)->group(function () {
    //filter posisi dan lokasi
    Route::get('/search', 'searchLowongan')->name('lowongan.search');
    //reset riwayat
    Route::post('/pelamar/reset-riwayat', 'resetRiwayat')->name('pelamar.resetRiwayat');
});



// REGION DROPDOWNS API
Route::get('/get-kota/{provinsi_id}', [AdminController::class, 'getKota'])->name('public.get.kota');
Route::get('/get-kecamatan/{kota_id}', [AdminController::class, 'getKecamatan'])->name('public.get.kecamatan');

//CV CONTROLLER
Route::controller(CVController::class)->group(function () {
    //DOWNLOAD CV
    Route::get('/cv/{pelamar:id}/download', 'downloadCv')->name('cv.download');
    Route::get('/cv/{pelamar:id}/preview', 'preview')->name('cv.preview');
});


//HALAMAN BERANDA USER BELUM LOGIN
Route::get('/', [AuthController::class, 'beranda']);


//LOGIN AUTH
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'login_non_user')->name('login');
    Route::post('/loginproses', 'loginproses')->name('loginproses');
});


Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/logout', 'logout_pelamar')->name('logout_pelamar');
});


Route::controller(AuthController::class)->group(function () {
    //register pelamar
    Route::get('/register', 'regis_non_user')->name('register');
    Route::post('/registerproses', 'regis_proses')->name('registerproses');
});



//VERFIKASI PASSWORD 
Route::controller(LupaPasswordController::class)->group(function () {
    Route::get('/verifikasi', 'showEmailForm_pelamar')->name('verifikasi_pelamar');
    Route::post('/verifikasi', 'sendOtp')->name('password.email.pelamar');

    Route::get('/verifikasi/otp/{token}', 'showOtpForm_pelamar')->name('password.otp.form.pelamar');
    Route::post('/verifikasi/otp', 'verifyOtp')->name('password.otp.verif.pelamar');

    Route::get('/reset-password/{token}', 'showResetForm_pelamar')->name('password.reset.form.pelamar');
    Route::post('/reset-password/{token}', 'resetPassword')->name('password.update.pelamar');

    Route::post('/verifikasi/otp/resend', 'resendOtp')->name('password.otp.resend.pelamar');
});


//VERIFIKASI EMAIL
Route::controller(EmailVerificationController::class)->middleware(['auth', 'email.role'])->group(function () {
    Route::get('email/ubah', 'showChangeEmailForm')->name('email.ubah');
    Route::post('/email/send-verification', 'sendVerification')->name('email.send.verification');
    // Route::get('/email/verify/{token}', 'verify')->name('email.verify');
});
Route::controller(EmailVerificationController::class)->group(function () {
    Route::get('/email/verify/{token}', 'verify')->name('email.verify');
});


//TIPS KERJA UNTUK USER BELUM LOGIN           
Route::controller(PelamarController::class)->group(function () {
    Route::get('/pelamar/tips-kerja', 'tips_kerja')->name('pelamar.tips-kerja');
    Route::get('/pelamar/tips-kerja/{tips}', 'detail')->name('pelamar.tips-kerja.show');
});

// SYARAT DAN KETENTUAN
Route::get('/images/syarat-dan-ketentuan-banner.png', function () {
    $uploadedPath = 'C:/Users/Fput/.gemini/antigravity/brain/61116b4b-cfa8-4d95-9a8f-a6ae6ea8ede6/.user_uploaded/media_1788007404614.png';
    $targetPath = public_path('images/syarat-ketentuan.png');

    if (file_exists($uploadedPath)) {
        if (!file_exists($targetPath)) {
            @copy($uploadedPath, $targetPath);
        }
        return response()->file($uploadedPath);
    }

    if (file_exists($targetPath)) {
        return response()->file($targetPath);
    }

    return response()->file(public_path('images/gambarkom.jpg'));
});

Route::get('/syarat-dan-ketentuan', function () {
    return view('layouts.syarat-dan-ketentuan');
})->name('syarat.ketentuan');

//EMAIL SUBSCRIBER
Route::controller(EmailSubController::class)->group(function () {
    Route::post('/email-subscribe', 'index')->name('subscribe.email');
});


Route::controller(PelamarController::class)->middleware('CheckUserStatus')->group(function () {
    Route::get('/pelamar/home', 'index')->name('beranda');
    Route::get('/pelamar/beranda', 'index')->name('pelamar.beranda');
    Route::get('/pelamar/detail-lowongan/{perusahaan}/{lowongan}', 'detail_lowongan_non_user')->name('detail.lowongan.non.user');
    Route::get('/pelamar/daftar-kandidat', 'daftar_kandidat')->name('pelamar.daftar-kandidat');
    Route::get('/lowongan-tersimpan', 'lowongansimpanform')->name('lowongan.tersimpan');
    Route::get('/lamaran-kerja', 'lamaranKerja')->name('pelamar.lamaran-kerja');
});

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('CheckUserStatus');

// EVENT UNTUK NON USER, PELAMAR & KANDIDAT
Route::controller(EventController::class)->group(function () {
    Route::get('/event', 'publicEventList')->name('pelamar.event.index');
    Route::get('/event/{id}', 'publicEventShow')->name('pelamar.event.show');
});

/**---------------------------------------------- PELAMAR PREFIX ---------------------------------------------------------------*/
Route::prefix('pelamar')->middleware('auth', 'role:pelamar', 'CheckUserStatus')->group(function () {

    //PELAMAR CONTROLLER
    Route::controller(PelamarController::class)->group(function () {
        //beranda

        //ganti password
        Route::post('/ganti-password', 'updatePassword')->name('pelamar.password.update');

        //riwayat pendidikan
        Route::post('/create/pendidikan', 'storependidikan')->name('pendidikan.store');
        Route::get('/edit/pendidikan/{riwayatpendidikan:id}', 'editpendidikan')->name('pendidikan.edit');
        Route::put('/update/pendidikan/{riwayatpendidikan:id}', 'updatependidikan')->name('pendidikan.update');
        Route::delete('/delete/pendidikan/{riwayatpendidikan:id}', 'destroypendidikan')->name('pendidikan.destroy');

        //simpan lowongan
        Route::post('/simpan-lowongan', 'store')->name('simpan-lowongan.store');
        Route::delete('/simpan-lowongan/{id}', 'destroy')->name('simpan-lowongan.destroy');

        //detail lowongan

        Route::get('/detail-lowongan/{slug}', 'detail_lowongan_non_userShare')->name('lowongan.show');

        //notifikasi lamaran pelamar
        Route::get('/notifikasi', 'notifikasi')->name('pelamar.notifikasi');

        //daftar kandidat
        Route::post('/kandidat/pendaftaran', 'storePendaftaran')->name('kandidat.storePendaftaran');
        Route::get('/kandidat/transaksi/{id}', 'transaksi')->name('kandidat.transaksi');
        Route::post('/kandidat/{id}/upload-bukti', 'uploadBukti')->name('kandidat.catatan_cash.upload_bukti');

        //Transaksi 
        Route::get('/transaksi', 'transaksiPendaftaranKandidat')->name('transaksi.pendaftaran');
    });

    Route::controller(PelamarLowonganController::class)->group(function () {
        //lamar
        Route::post('/lamar-cepat/{lowongan}', 'storeQuick')->name('lamar.cepat');
    });


    //PROFILE CONTROLLER
    Route::controller(ProfileController::class)->group(function () {
        //profile
        Route::get('/edit/profile', 'edit')->name('profile.edit');
        Route::put('/update/profile/{pelamar:id}', 'update_profile')->name('profile.update');
        Route::delete('/delete/profile/{pelamar:id}', 'destroy_profile')->name('profile.destroy');

        //update kategori pelamar
        Route::put('/update-kategori/{id}', 'updateKategori')->name('kategori.update');

        //alamat pelamar
        Route::get('/alamat', 'alamat')->name('alamat');
        Route::get('/form/alamat', 'form_alamat')->name('form_alamat');
        Route::post('/create/alamat', 'store_alamat')->name('alamat.store');
        Route::get('/edit/alamat/{alamatpelamar:id}', 'edit_alamat')->name('alamat.edit');
        Route::put('/update/alamat/{alamatpelamar:id}', 'update_alamat')->name('alamat.update');
        Route::delete('/delete/alamat/{alamatpelamar:id}', 'destroy_alamat')->name('alamat.destroy');
    });


    //PENGALAMAN ORGANISASI CONTROLLER
    Route::controller(PengalamanOrgController::class)->group(function () {
        //pengalaman organisasi
        Route::post('/create/organisasi', 'store')->name('organisasi.store');
        Route::get('/edit/organisasi/{organisasi:id}', 'edit')->name('organisasi.edit');
        Route::put('/update/organisasi/{organisasi:id}', 'update')->name('organisasi.update');
        Route::delete('/delete/organisasi/{organisasi:id}', 'destroy')->name('organisasi.destroy');
    });


    //pengalaman kerja
    Route::controller(PengalamanKerjaController::class)->group(function () {
        //pengalaman kerja
        Route::post('/create/kerja', 'store')->name('kerja.store');
        Route::get('/edit/kerja/{kerja:id}', 'edit')->name('kerja.edit');
        Route::put('/update/kerja/{kerja:id}', 'update')->name('kerja.update');
        Route::delete('/delete/kerja/{kerja:id}', 'destroy')->name('kerja.destroy');
    });


    //SKILL
    Route::controller(SkillController::class)->group(function () {
        //skill
        Route::post('/create/skill', 'store')->name('skill.store');
        Route::get('/edit/skill/{skill:id}', 'edit')->name('skill.edit');
        Route::put('/update/skill/{skill:id}', 'update')->name('skill.update');
        Route::delete('/delete/skill/{skill:id}', 'destroy')->name('skill.destroy');
    });


    //KANDIDAT CONTROLLER
    Route::controller(KandidatController::class)->group(function () {
        //CALON KANDIDAT
        Route::get('/calon/kandidat/pelatihan', 'rekrutHalKosong')->name('pelamar.calon-kandidat.pelatihan')->middleware('cekKategori:calon kandidat');
    });


    //PEMBELIAN KANDIDAT
    Route::controller(PembeliKandidatController::class)->group(function () {
        //tawaran
        Route::get('/tawaran', 'tawaran')->name('pelamar.tawaran');
        Route::get('/kandidat/tawaran/{perusahaan}/{lowongan}', 'detailTawaran')
            ->name('kandidat.detailTawaran');
        Route::post('/pembeli_kandidat/{id}/status', 'updateStatus')->name('kandidat.updateStatus');
    });
});
/**---------------------------------------- END PELAMAR PREFIX -------------------------------------*/

//PELAMAR CONTROLLER
Route::controller(PelamarController::class)->group(function () {
    //notifikasi
    Route::post('/notifikasi/baca/semua', 'bacaSemua')->name('notifikasi.bacaSemua');
    Route::post('/notifikasi/baca/{id}', 'baca')->name('notifikasi.baca');
    Route::delete('/notifikasi/hapus/{id}', 'hapus')->name('notifikasi.hapus');
    Route::delete('/notifikasi/hapus-semua', 'hapusSemua')->name('notifikasi.hapusSemua');
    Route::delete('/notifikasi/hapus-semua-baca', 'hapusSemuaBaca')->name('notifikasi.hapusSemuaBaca');

    //pasang lowongan
    Route::get('/lowongan', 'pasangLowongan')->name('pelamar.pasangLowongan');

    //talent hunter
    Route::get('/talent-hunter', 'talentHunter')->name('pelamar.talentHunter');

    //bantuan
    Route::get('/bantuan', 'bantuan')->name('pelamar.bantuan');

    //syarat dan ketentuan
    Route::get('/syarat/ketentuan', 'syaratKetentuan')->name('pelamar.syaratKetentuan');
});
//TIPS KERJA CONTROLLER
Route::controller(TipsKerjaController::class)->group(function () {
    //tips kerja
    Route::get('/tips1', 'tips_kerja_tips1')->name('tips-kerja.tips1');
});






    







/**---------------------------------------- FINANCE PREFIX -------------------------------------*/
//Finance PREFIX
Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/logout/finance', 'logout_finance')->name('logout_finance');
});

Route::prefix('finance')->middleware('auth', 'role:finance', 'CheckUserStatus')->group(function () {

    //AUTH CONTROLLER
    Route::controller(AuthController::class)->group(function () {
        //beranda finance
        Route::get('/dashboard', 'beranda_finance')->name('finance.dashboard');
    });

    //CATATAN CASH CONTROLLER
    Route::controller(CatatanCashController::class)->group(function () {
        //update status topup
        Route::post('/topup/{id}/update-status', 'updateStatus')->name('catatan_cash.update_status');
    });


    //PAKET HARGA
    Route::controller(HargaController::class)->group(function () {
        //paket harga
        Route::get('/paket/harga', 'index')->name('finance.paket-harga');

        //edit harga koin
        Route::get('/paketharga/edit/koin', 'edit_koin')->name('finance.paket-harga.edit-koin');

        //update harga koin
        Route::put('/update/harga/koin', 'update_koin')->name('finance.paket-harga.update-koin');

        //edit harga pembayaran
        Route::get('/paketharga/edit/harga', 'edit_pembayaran')->name('finance.paket-harga.edit-pembayaran');
        Route::put('/update/harga/harga', 'update_pembayaran')->name('finance.paket-harga.update-pembayaran');
    });


    //FINANCE CONTROLLER
    Route::controller(FinanceController::class)->group(function () {
        //OMSET PERUSAHAAN PERBULAN
        Route::get('/omset', 'omset_perusahaan')->name('finance.omset');
        Route::get('/finance/omset/unduh', 'unduh_omset')->name('finance.omset.unduh');

        //lap transaksi
        Route::get('/laporan/transaksi', 'laporan')->name('finance.catatan');

        //laporan transaksi
        Route::get('/laporan', 'laporan_transaksi')->name('finance.laporan');
        Route::get('/finance/laporan/detail/{tanggal}', 'detail_laporan')->name('finance.laporan.detail');
        Route::get('/finance/laporan/{tanggal}/unduh', 'unduh_laporan_harian')->name('finance.laporan.unduh');

        //detail catatan koin
        Route::get('/detail', 'hal_detail')->name('finance.detail.catatan.koin');
        Route::get('/detail/{id}', 'detail')->name('finance.detail.id');
        Route::post('/verifikasi/{id}', 'verifikasi')->name('finance.verifikasi');

        //unduh omset pdf
        Route::get('/page/unduh/omset', 'pageUnduhOmset')->name('finance.omset.unduh.pdf');
    });
});
/**---------------------------------------- END FINANCE PREFIX -------------------------------------*/











/**---------------------------------------- ADMIN PREFIX -------------------------------------*/
//Admin PREFIX
Route::controller(AuthController::class)->middleware('auth')->group(function () {
    Route::post('/logout/admin', 'logout_admin')->name('logout_admin');
});


Route::prefix('admin')->middleware('auth', 'role:admin', 'CheckUserStatus')->group(function () {

    //AUTH CONTROLLER
    Route::controller(AuthController::class)->group(function () {
        Route::get('/dashboard', 'beranda_admin')->name('admin.dashboard');
    });

    //ADMIN CONTROLLER
    Route::controller(AdminController::class)->group(function () {

        //profile admin
        Route::get('/profile', 'profile_admin')->name('admin.profile');
        Route::get('/edit/profile', 'edit_profile')->name('admin.edit.profile');
        Route::put('/update/profile/{admin:id}', 'update_profile_admin')->name('admin.update.profile');
        Route::delete('/delete/profile/{admin:id}', 'destroy_profile')->name('admin.destroy.profile');

        //calon kandidat
        Route::get('/calon/kandidat', 'halCalonKandidat')->name('admin.calon-kandidat');
        Route::get('/calon-kandidat/{id}', 'detailCalonKandidat')->name('calon.detail');
        Route::post('/calon-kandidat/{id}/update', 'updateTraining')->name('calon.update');
        Route::post('/calon-kandidat/{id}/lulus', 'lulus')->name('calon.lulus');
        Route::post('/calon-kandidat/{id}/gugur', 'gugur')->name('calon.gugur');

        //non kandidat
        Route::get('/non/kandidat', 'halNonKandidat')->name('admin.non-kandidat');
        Route::get('/non-kandidat/{id}', 'detailNonKandidat')->name('admin.detail.non.kandidat');

        //kandidat
        Route::get('/kandidat', 'halKandidat')->name('admin.kandidat');
        Route::get('/kandidat/{id}', 'detailKandidat')->name('admin.detail.kandidat');

        //finance
        Route::get('/finance', 'koinHal')->name('admin.finance');
        Route::get('/finance/koin/detail/{id}', function ($id) {
            $data = App\Models\CatatanKoin::findOrFail($id);
            return response()->json($data);
        });
        Route::get('/finance/tunai', 'cashHal')->name('admin.finance.cash');

        //provinsi kota kecamatan
        Route::get('/get-kota/{provinsi_id}', 'getKota')->name('admin.get.kota')->middleware('auth');
        Route::get('/get-kecamatan/{kota_id}', 'getKecamatan')->name('admin.get.kecamatan')->middleware('auth');


        //perusahaan admin
        Route::get('/perusahaan', 'halPerusahaan')->name('admin.perusahaan');
        Route::get('/perusahaan/detail/{id}', 'detailPerusahaan')->name('admin.perusahaan.detail');
        Route::get('/admin/lowongan/{perusahaan}/{lowongan}', 'detailLowongan')->name('admin.lowongan.detail');
        Route::get('/perusahaan/talent/hunter', function () {
            return view('perusahaan.talenthunter-perusahaan');
        });

        //freeze akun
        Route::post('/user/freeze/{id}', 'bekukan')->name('admin.freeze');
        Route::post('/user/unfreeze/{id}', 'aktifkan')->name('admin.unfreeze');


        //talent hunter
        Route::get('/talent/hunter', 'talentHunterForm')->name('admin.talent-hunter');
        Route::get('/talent/hunter/detail/{id}', 'detailTalentHunter')->name('admin.talent-hunter.detail');


        //recruitment
        Route::get('/recruitment/perusahaan', 'halPerusahaanRecruitment')->name('admin.recruitment.perusahaan');
        Route::get('/recruitment/{id}', 'recruitment')->name('admin.recruitment');
        Route::get('/recruitment/{id}/detail', 'detailRecruitment')->name('admin.recruitment.detail');
        Route::delete('/recruitment/{id}/hapus', 'destroyRecruitment')->name('admin.recruitment.destroy');


        //FILTER PROVINSI
        // Pilih Provinsi
        Route::get('/dashboard/pilih-provinsi', 'pilihProvinsi')->name('dashboard.pilih-provinsi');

        // Set Provinsi (Session)
        Route::post('/dashboard/set-provinsi', 'setProvinsi')->name('dashboard.set-provinsi');
    });


    //TIPS KERJA CONTROLLER
    Route::controller(TipsKerjaController::class)->group(function () {
        //tips kerja post
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


    //EVENT CONTROLLER
    Route::controller(EventController::class)->group(function () {
        //event
        Route::get('/event', 'index_admin')->name('admin.eventform');
        Route::post('/event/store', 'store_event_admin')->name('admin.event.store');
        Route::get('/event/create', 'createForm_admin')->name('admin.event.createForm');
        Route::put('/update/event/{event}', 'update_event_admin')->name('admin.event.update');
        Route::get('/event/{event}', 'detail_admin')->name('admin.detail.event');
        Route::get('/event/{event}/edit', 'edit_admin')->name('admin.edit.event');
        Route::delete('/delete/event/{event}', 'destroy_admin')->name('admin.event.destroy');

        Route::put('/events/status/{event}', 'updateStatus')->name('admin.event.updateStatus');
    });


    //LOWONGAN PERUSAHAAN CONTROLLER
    Route::controller(LowonganPerusahaanController::class)->group(function () {
        //rekomendasi lowongan
        Route::post('/lowongan/{id}/rekomendasi', 'toggleRekomendasi')->name('admin.lowongan.toggleRekomendasi');
    });

    //Verifikasi Perusahaan
    Route::controller(VerifikasiPerusahaanController::class)->group(function () {
        Route::post('/perusahaan/approve/{id}', 'approve')->name('admin.perusahaan.approve');
        Route::post('/perusahaan/reject/{id}', 'reject')->name('admin.perusahaan.reject');
    });
});
/**---------------------------------------- END ADMIN PREFIX -------------------------------------*/















/**---------------------------------------- SUPER ADMIN PREFIX -------------------------------------*/
//Super Admin PREFIX
Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/logout/superadmin', 'logout_superadmin')->name('logout_superadmin');
});


Route::prefix('super_admin')->middleware('auth', 'role:super_admin', 'CheckUserStatus')->group(function () {

    Route::controller(SuperAdminController::class)->group(function () {

        //dashboard
        Route::get('/dashboard', 'index')->name('superadmin.dashboard');

        //pengaturan
        Route::get('/pengaturan', 'pengaturan')->name('superadmin.pengaturan');

        //profile
        Route::get('/profile', 'profile_superadmin')->name('superadmin.profile');
        Route::get('/edit/profile', 'edit_profile')->name('superadmin.edit.profile');
        Route::put('/update/profile/{superadmin:id}', 'update_profile_superadmin')->name('superadmin.update.profile');
        Route::delete('/delete/profile/{superadmin:id}', 'destroy_profile')->name('superadmin.destroy.profile');

        //freeze akun
        Route::get('/freeze', 'freezeForm')->name('superadmin.freeze');
        Route::get('/freeze/detail/{user:id}', 'detail_freeze')->name('superadmin.detail.freeze');
        Route::put('/freeze/ban/{user:id}', 'ban')->name('superadmin.ban.freeze');
        Route::put('/freeze/unban/{user:id}', 'unban')->name('superadmin.unban.freeze');
        Route::delete('/delete/akun/{user:id}', 'delete_akun')->name('superadmin.delete.akun');

        //Pelamar
        Route::get('/pelamar', 'pelamarhal')->name('superadmin.pelamar');
        Route::get('/pelamar/tambah/{kategori}', 'createKategori')->where('kategori', '(kandidat|non_kandidat|calon_kandidat)')->name('superadmin.pelamar.create');
        Route::post('/pelamar/store', 'storeUser')->name('superadmin.pelamar.store');
        Route::get('/pelamar/edit/{kategori}/{id}', 'editUser')->where('kategori', '(non_kandidat|calon_kandidat|kandidat)')->name('superadmin.pelamar.edit');
        Route::put('/pelamar/update/{id}', 'updateUser')->name('superadmin.pelamar.update');
        Route::delete('/pelamar/{id}', 'destroyUser')->name('superadmin.pelamar.destroy');

        //kandidat
        Route::get('/kandidat/{pelamar}', 'detail_kandidat')->name('superadmin.detail.kandidat');

        //non kandidat
        Route::get('/non-kandidat/{pelamar}', 'detail_non_kandidat')->name('superadmin.detail.non.kandidat');
        Route::get('/non-kandidat/{pelamar}/edit', 'edit_non_kandidat')->name('superadmin.edit.non.kandidat');

        //calon kandidat
        Route::get('/calon-kandidat/{id}', 'detailCalonKandidat')->name('superadmin.calon.detail');
        Route::post('/calon-kandidat/{id}/update', 'updateTraining')->name('superadmin.calon.update');
        Route::post('/calon-kandidat/{id}/lulus', 'lulus')->name('superadmin.calon.lulus');
        Route::post('/calon-kandidat/{id}/gugur', 'gugur')->name('superadmin.calon.gugur');

        //crud admin dan finance
        Route::get('/add/user', 'role')->name('superadmin.add.user');
        Route::get('/add/user/createForm', 'createForm')->name('superadmin.add.user.createForm');
        Route::post('/add/user/store', 'store')->name('superadmin.add.user.store');
        Route::get('/edit/user/{id}', 'edit')->name('superadmin.edit.user');
        Route::put('/update/user/{id}', 'update')->name('superadmin.update.user');
        Route::get('/detail/user/{id}', 'detail')->name('superadmin.detail.user');
        Route::delete('/delete/user/{id}', 'hapus')->name('superadmin.destroy.user');

        //Pengaturan
        Route::post('/ganti-password', 'updatePassword')->name('superadmin.password.update');

        //perusahaan
        Route::get('/perusahaan', 'halPerusahaan')->name('superadmin.perusahaan');
        Route::get('/perusahaan/{id}', 'detailPerusahaan')->name('superadmin.perusahaan.detail');
        Route::get('/lowongan/{perusahaan}/{lowongan}', 'detailLowongan')->name('superadmin.lowongan.detail');

        //paket harga
        Route::get('/paket/harga', 'halFinance')->name('superadmin.paket-harga');

        Route::get('/paket/harga/edit/koin', 'edit_koin')->name('superadmin.paket-harga.edit-koin');
        Route::put('/update/harga/koin', 'update_koin')->name('superadmin.paket-harga.update-koin');

        Route::get('/paket/harga/edit/harga', 'edit_pembayaran')->name('superadmin.paket-harga.edit-pembayaran');
        Route::put('/update/harga/harga', 'update_pembayaran')->name('superadmin.paket-harga.update-pembayaran');

        //Detail Laporan Finance
        Route::get('/laporan/detail/{tanggal}', 'detail_laporan')->name('superadmin.laporan.detail');
        Route::get('/laporan/{tanggal}/unduh', 'unduh_laporan_harian')->name('superadmin.laporan.unduh');

        //Panggilan
        Route::get('/panggilan', 'panggilan')->name('superadmin.panggilan');
        Route::get('/panggilan/{perusahaan_id}/list', 'listPekerja')->name('superadmin.panggilan.list');

        //recruitment
        Route::get('/recruitment/perusahaan', 'recruitmentPerusahaan')->name('superadmin.recruitment.perusahaan');
        Route::get('/recruitment/{id}', 'recruitment')->name('superadmin.recruitment');
        Route::get('/recruitment/{id}/detail', 'detailRecruitment')->name('superadmin.recruitment.detail');
        Route::delete('/recruitment/{id}/hapus', 'destroyRecruitment')->name('superadmin.recruitment.destroy');

        //talent hunter
        Route::get('/talent/hunter', 'talentHunterForm')->name('superadmin.talent-hunter');
        Route::get('/talent/hunter/{id}', 'detailDataTalentHunter')->name('superadmin.talent-hunter.detail');
    });



    //TIPS KERJA CONTROLLER
    Route::controller(TipsKerjaController::class)->group(function () {
        //tips kerja
        Route::get('/tips/kerja', 'index_superadmin')->name('superadmin.tips-kerja');
        Route::post('/tips/kerja/', 'store_tips_kerja_superadmin')->name('superadmin.tips-kerja.store');
        Route::get('/tips/kerja/create', 'tips_kerja_buat_post_superadmin')->name('superadmin.tips-kerja.createForm');
        Route::put('/update/status/', 'update_status_superadmin')->name('superadmin.tips-kerja.update.status');
        Route::delete('/delete', 'destroy_superadmin')->name('superadmin.tips-kerja.destroy');;
    });



    //EVENT CONTROLLER
    Route::controller(EventController::class)->group(function () {
        //event
        Route::get('/event', 'index')->name('superadmin.eventform');
        Route::post('/event/store', 'store_event')->name('superadmin.event.store');
        Route::get('/event/create', 'createForm')->name('superadmin.event.createForm');
        Route::put('/update/event/{event}', 'update_event')->name('superadmin.event.update');
        Route::get('/event/{event}', 'detail')->name('superadmin.detail.event');
        Route::get('/event/{event}/edit', 'edit')->name('superadmin.edit.event');
        Route::delete('/delete/event/{event}', 'destroy')->name('superadmin.event.destroy');
        Route::put('/events/status/{event}', 'updateStatus')->name('event.updateStatus');
    });



    //ADMIN CONTROLLER
    Route::controller(AdminController::class)->group(function () {
        //kecamatan kota
        Route::get('/get-kota/{provinsi_id}', 'getKota')->name('superadmin.get.kota')->middleware('auth');
        Route::get('/get-kecamatan/{kota_id}', 'getKecamatan')->name('superadmin.get.kecamatan')->middleware('auth');
    });



    //LOWONGAN CONTROLLER
    Route::controller(LowonganPerusahaanController::class)->group(function () {
        //lowongan
        Route::get('/perusahaan/{id}/createform/lowongan', 'createFormSuper')->name('superadmin.lowongan.create.form');
        Route::post('/perusahaan/{id}/buat/lowongan', 'storeSuper')->name('superadmin.lowongan.saya.store');
        Route::get('/edit/lowongan/{lowongan}', 'editSuper')->name('superadmin.lowongan.edit.form');
        Route::put('/update/lowongan/{lowongan}', 'updateSuper')->name('superadmin.lowongan.update');
        Route::delete('/lowongan/{lowongan}', 'destroySuper')->name('superadmin.lowongan.destroy');

        //rekomendasi
        Route::post('/lowongan/{id}/rekomendasi', 'toggleRekomendasi')->name('superadmin.lowongan.toggleRekomendasi');
    });



    //PROFILE CONTROLLER
    Route::controller(ProfileController::class)->group(function () {
        //alamat
        Route::post('/create/alamat', 'store_alamatSuper')->name('superadmin.alamat.store')->middleware('auth');
        Route::get('/edit/alamat/{alamatpelamar:id}', 'edit_alamatSuper')->name('superadmin.alamat.edit')->middleware('auth');
        Route::put('/update/alamat/{alamatpelamar?}', 'update_alamatSuper')->name('superadmin.alamat.update')->middleware('auth');
        Route::delete('/delete/alamat/{alamatpelamar:id}', 'destroy_alamatSuper')->name('superadmin.alamat.destroy')->middleware('auth');
    });



    //PELAMAR CONTROLLER
    Route::controller(PelamarController::class)->group(function () {
        //riwayat pendidikan
        Route::post('/create/pendidikan', 'storependidikanSuper')->name('superadmin.pendidikan.store')->middleware('auth');
        Route::get('/edit/pendidikan/{riwayatpendidikan:id}', 'editpendidikanSuper')->name('superadmin.pendidikan.edit')->middleware('auth');
        Route::put('/update/pendidikan/{riwayatpendidikan?}', 'updatependidikanSuper')->name('superadmin.pendidikan.update');
        Route::delete('/delete/pendidikan/{riwayatpendidikan:id}', 'destroypendidikanSuper')->name('superadmin.pendidikan.destroy')->middleware('auth');
    });



    //PENGALAMAN ORGANISASI CONTROLLER
    Route::controller(PengalamanOrgController::class)->group(function () {
        //pengalaman organisasi
        Route::post('/create/organisasi', 'storeSuper')->name('superadmin.organisasi.store')->middleware('auth');
        Route::get('/edit/organisasi/{organisasi:id}', 'editSuper')->name('superadmin.organisasi.edit')->middleware('auth');
        Route::put('/update/organisasi/{organisasi?}', 'updateSuper')->name('superadmin.organisasi.update')->middleware('auth');
        Route::delete('/delete/organisasi/{organisasi:id}', 'destroySuper')->name('superadmin.organisasi.destroy')->middleware('auth');
    });


    //Pengalaman Kerja CONTROLLER
    Route::controller(PengalamanKerjaController::class)->group(function () {
        //pengalaman kerja
        Route::post('/create/kerja', 'storeSuper')->name('superadmin.kerja.store')->middleware('auth');
        Route::get('/edit/kerja/{kerja:id}', 'editSuper')->name('superadmin.kerja.edit')->middleware('auth');
        Route::put('/update/kerja/{kerja?}', 'updateSuper')->name('superadmin.kerja.update')->middleware('auth');
        Route::delete('/delete/kerja/{kerja:id}', 'destroySuper')->name('superadmin.kerja.destroy')->middleware('auth');
    });


    //SKILL CONTROLLER
    Route::controller(SkillController::class)->group(function () {
        //skill
        Route::post('/create/skill', 'storeSuper')->name('superadmin.skill.store')->middleware('auth');
        Route::get('/edit/skill/{skill:id}', 'editSuper')->name('superadmin.skill.edit')->middleware('auth');
        Route::put('/update/skill/{skill?}', 'updateSuper')->name('superadmin.skill.update')->middleware('auth');
        Route::delete('/delete/skill/{skill:id}', 'destroySuper')->name('superadmin.skill.destroy')->middleware('auth');
    });


    //MANAJEMEN LOWONGAN CONTROLLER
    Route::controller(ManajemenLowonganController::class)->group(function () { 
        //manajemen lowongan
        Route::get('/manajemen/lowongan/gold', 'gold')->name('superadmin.manajemen.lowongan.gold')->middleware('auth');
        Route::get('/manajemen/lowongan/silver', 'silver')->name('superadmin.manajemen.lowongan.silver')->middleware('auth');
        Route::get('/manajemen/lowongan/bronze', 'bronze')->name('superadmin.manajemen.lowongan.bronze')->middleware('auth');

        Route::post('/manajemen/lowongan/gold/update', 'updateGold')->name('superadmin.manajemen.lowongan.gold.update')->middleware('auth');
        Route::post('/manajemen/lowongan/silver/update', 'updatSilver')->name('superadmin.manajemen.lowongan.silver.update')->middleware('auth');
        Route::post('/manajemen/lowongan/bronze/update', 'updateBronze')->name('superadmin.manajemen.lowongan.bronze.update')->middleware('auth');
    });

    //SOCIAL LINK CONTROLLER
    Route::controller(SocialLinkController::class)->group(function () {
        //social link
        Route::get('/social-links', 'index')->name('superadmin.social.index')->middleware('auth');
        Route::post('/social-links', 'update')->name('superadmin.social.update')->middleware('auth');

        //header image
        Route::get('/header-image', 'headerImageIndex')->name('superadmin.header.image')->middleware('auth');
        Route::put('/header-image/{nama}', 'headerImageUpdate')->name('superadmin.header.update')->middleware('auth');
    });


    //TALENT HUNTER CONTROLLER
    Route::controller(TalentHunterController::class)->group(function () {
        //talent hunter
        Route::get('/talent-hunter/{id}/edit', 'editTalentHunter')->name('superadmin.talent-hunter.edit')->middleware('auth');
        Route::put('/talent-hunter/{id}', 'update')->name('superadmin.talent-hunter.update')->middleware('auth');
    });


    //Email SUbs
    Route::controller(EmailSubController::class)->group(function () {
        //email subs
        Route::get('/email-subs', 'halEmail')->name('superadmin.email-subs.index');
        Route::get('/email-subscribers/pdf', 'downloadPdf')->name('superadmin.email-subscribers.pdf');
        Route::delete('/email-subs/bulk-delete', 'bulkDelete')->name('superadmin.email-subs.bulk-delete');
    });

    //Verifikasi Perusahaan 
    Route::controller(VerifikasiPerusahaanController::class)->group(function () {
        Route::post('/perusahaan/approve/{id}', 'approveSuper')->name('superadmin.perusahaan.approve');
        Route::post('/perusahaan/reject/{id}', 'rejectSuper')->name('superadmin.perusahaan.reject');
    });
});
/**---------------------------------------- END SUPER ADMIN PREFIX -------------------------------------*/















/**---------------------------------------- PERUSAHAAN PREFIX -------------------------------------*/
Route::controller(LowonganPerusahaanController::class)->group(function () {
    //lowongan
    Route::get(
        '/perusahaan/{perusahaan:slug}/lowongan/{lowongan:slug}',
        'show'
    )->name('lowongan.detail')->middleware('auth', 'CheckUserStatus');
});


Route::prefix('perusahaan')->middleware('auth', 'role:perusahaan', 'CheckUserStatus')->group(function () {

    //AUTH CONTROLLER
    Route::controller(AuthController::class)->group(function () {
        //beranda perusahaan
        Route::get('/dashboard', 'beranda_perusahaan')->name('perusahaan.dashboard');
    });


    //Perusahaan CONTROLLER 
    Route::controller(PerusahaanController::class)->group(function () {
        //profile perusahaan
        Route::get('/profile', 'profile_perusahaan')->name('profile.perusahaan');
        Route::get('/edit/profile', 'edit_profile')->name('profile.edit.perusahaan');
        Route::put('/update/profile/{perusahaan:id}', 'update_profile_perusahaan')->name('profile.update.perusahaan');
        Route::delete('/delete/profile/{perusahaan:id}', 'destroy_profile')->name('profile.destroy.perusahaan');

        //alamat perusahaan
        Route::get('/alamat', 'alamat_perusahaan')->name('alamat.perusahaan');
        Route::get('/form/alamat', 'form_alamat')->name('form.alamat.perusahaan');

        Route::post('/alamat-perusahaan/{id}/utama', 'setUtama')->name('alamat-perusahaan.setUtama');

        Route::post('/create/alamat', 'store_alamat')->name('alamat.store.perusahaan');
        Route::get('/edit/alamat/{id}', 'edit_alamat')->name('alamat.edit.perusahaan');
        Route::put('/update/alamat/{id}', 'update_alamat')->name('alamat.update.perusahaan');
        Route::delete('/delete/alamat/{id}', 'destroy_alamat')->name('alamat.destroy.perusahaan');

        //pelamar
        Route::get('pelamar/{lowongan:slug}', 'pelamar')->name('perusahaan.pelamar');

        //pengaturan perusahaan
        Route::get('/pengaturan', 'pengaturanForm')->name('perusahaan.pengaturan');

        //kandidat ak
        Route::get('/kandidat/ak', 'kandidat_ak')->name('perusahaan.kandidat.ak');

        //event
        Route::get('/event', 'event')->name('perusahaan.event.index');
        Route::get('/gabung/event/{id}', 'detail')->name('perusahaan.event.show');

        //berlangganan
        Route::get('/berlangganan', 'halLangganan')->name('perusahaan.berlangganan');

        //Kandidat Saya
        Route::get('/recruitment/kandidat-saya', 'kandidatSaya')->name('perusahaan.kandidat.saya');

        //daftar pekerja bermasalah
        Route::get('/data/pekerja', 'halDaftarPekerja')->name('perusahaan.data.pekerja');
        Route::get('/data/pekerja-bermasalah', 'listPekerjaBermasalah')->name('perusahaan.data.pekerja-bermasalah');
        Route::get('/cari-nama-pekerja', 'halCariNamaPekerja')->name('perusahaan.cari.nama.pekerja');
        Route::get('/laporan-harian', 'halLaporanHarianPekerja')->name('perusahaan.laporan.harian');
    });


    //PERUSAHAAN CONTROLLER WITH VERIFIED MIDDLEWARE
    Route::controller(PerusahaanController::class)->middleware('perusahaan.verified')->group(function () {

        //pengaturan perusahaan
        Route::post('/ganti-password', 'updatePassword')->name('password.update');

        //berlangganan
        Route::post('/berlangganan', 'storeLangganan')->name('berlangganan.store');
        //kirim email langganan
        Route::post('/send-email', 'kirimEmail')->name('send.email')->middleware('auth');

        //Kandidat Saya
        Route::delete('/recruitment/{id}/hapus', 'destroyRecruitmentPerusahaan')->name('perusahaan.destroy.kandidat');

        //diskon fitur
        Route::post('/diskon-fitur', 'DiskonFitur')->name('diskon.fitur');
        //pekerja bermasalah wa
        Route::post('/pekerja-bermasalah/wa', 'PekerjaBermasalahWA')->name('perusahaan.pekerja.bermasalah.wa');
        //laporan harian pekerja wa
        Route::post('/laporan-harian/pekerja/wa', 'LaporanHarianPekerjaWA')->name('perusahaan.laporan.harian.pekerja.wa');
        //cari nama pekerja wa
        Route::post('/cari-nama-pekerja/wa', 'CariPekerjaWA')->name('perusahaan.cari.nama.pekerja.wa');
    });


    //CATATAN CASH CONTROLLER
    Route::controller(CatatanCashController::class)->middleware('perusahaan.verified')->group(function () {
        //top up
        Route::post('/topup/store', 'store')->name('catatan_cash.store');
        Route::get('/topup/{id}', 'show')->name('catatan_cash.show');
        Route::post('/topup/{id}/upload-bukti', 'uploadBukti')->name('catatan_cash.upload_bukti');
    });


    //LOWONGAN CONTROLLER
    Route::controller(LowonganPerusahaanController::class)->group(function () {
        //lowongan saya
        Route::get('/lowongan', 'index')->name('lowongan.saya.perusahaan');

        //paket lowongan
        Route::get('/paket/form', 'paketform')->name('paket.form');
    });


    //LOWONGAN CONTROLLER WITH VERIFIED MIDDLEWARE
    Route::controller(LowonganPerusahaanController::class)->middleware('perusahaan.verified')->group(function () {
        //lowongan saya
        Route::get('/createform/lowongan', 'createForm')->name('lowongan.create.form');
        Route::post('/buat/lowongan', 'store')->name('lowongan.saya.store');
        Route::get('/edit/lowongan/{perusahaan}/{lowongan}', 'edit')->name('lowongan.edit.form');
        Route::put('/update/lowongan/{lowongan:id}', 'update')->name('lowongan.update');
        Route::delete('/lowongan/{lowongan:id}', 'destroy')->name('lowongan.destroy');
        Route::put('/lowongan/{id}/toggle-status', 'toggleStatus')->name('lowongan.toggleStatus');

        //paket lowongan
        Route::post('/paket/beli', 'beliPaket')->name('paket.beli');
        Route::post('/lowongan/{lowongan}/publish', 'publish')->name('lowongan.publish');

        //Booster Lowongan
        Route::post('/boost-lowongan', 'boost')->name('boost.lowongan');
    });



    //PELAMAR CONTROLLER wiTH VERIFIED MIDDLEWARE
    Route::controller(PelamarController::class)->middleware('perusahaan.verified')->group(function () {
        //pelamar lowongan
        Route::get('/pelamar/{pelamarlowongan}/konfirmasihal', 'konfirmasi_hal')->name('pelamar.konfirmasi');
        Route::post('/pelamar/{pelamarlowongan}/konfirmasi', 'konfirmasi_simpan')->name('pelamar.konfirmasi.simpan');
        Route::post('/pelamar/{pelamarlowongan}/kirim', 'kirim')->name('pelamar.konfirmasi.kirim');
        Route::post('/pelamar/{pelamarlowongan}/tolak', 'tolak')->name('pelamar.tolak');
        Route::get('/pelamar/{pelamarlowongan}/detail', 'preview')->name('pelamar.detail');
    });


    //PEMBELI KANDIDAT CONTROLLER
    Route::controller(PembeliKandidatController::class)->middleware('perusahaan.verified')->group(function () {
        //kandidat ak
        Route::post('/kandidat/beli', 'beli')->name('kandidat.beli');
    });


    //TALENT HUNTER CONTROLLER
    Route::controller(TalentHunterController::class)->group(function () {
        //talent hunter
        Route::get('/talent-hunter', 'index')->name('talent-hunter.index');
    });
    //TALENT HUNTER CONTROLLER WITH VERIFIED MIDDLEWARE
    Route::controller(TalentHunterController::class)->middleware('perusahaan.verified')->group(function () {
        //talent hunter
        Route::get('/talent-hunter/harga', 'getHarga')->name('talent-hunter.harga');
        Route::post('/talent-hunter/beli', 'beli')->name('talent-hunter.beli');
        Route::post('/talent-hunter/store', 'store')->name('talent-hunter.store');
    });
});
/**---------------------------------------- END PERUSAHAAN PREFIX -------------------------------------*/

//Perusahaan Controller
Route::controller(PerusahaanController::class)->group(function () {
    //provinsi kecamatan kota
    Route::get('/get-kota/{provinsi_id}', 'getKota')->name('get.kota')->middleware('auth');
    Route::get('/get-kecamatan/{kota_id}', 'getKecamatan')->name('get.kecamatan')->middleware('auth');
});

//logout perusahaan
Route::controller(AuthController::class)->middleware('auth')->group(function () {
    Route::post('/logout/perusahaan', 'logout_perusahaan')->name('logout_perusahaan');
});

//register perusahaan
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::post('/registerproses_perusahaan', 'regis_proses_perusahaan')->name('registerproses_perusahaan');
});
