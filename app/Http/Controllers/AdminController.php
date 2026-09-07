<?php

namespace App\Http\Controllers;

use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use App\Models\Provinsi;
use App\Models\TalentHunter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()
    {
        return $this->beranda_admin();
    }

    public function beranda_admin()
    {
        return app(AuthController::class)->beranda_admin();
    }

    public function profile_admin()
    {
        return view('admin.profile.profile');
    }

    public function edit_profile($id = null)
    {
        $user = Auth::user();
        $provinsis = collect();

        try {
            if (Schema::hasTable('provinsis')) {
                $provinsis = DB::table('provinsis')->get();
            }
        } catch (\Throwable $e) {
            $provinsis = collect();
        }

        if ($provinsis->isEmpty() && file_exists(database_path('data/provinces.json'))) {
            $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
            $provinsis = collect($json)->map(function ($item) {
                return (object)[
                    'id'   => (string)$item['id'],
                    'nama' => ucwords(strtolower($item['name'])),
                ];
            });
        }

        return view('admin.profile.edit-profile', [
            "data"      => $user->admin,
            'provinsis' => $provinsis,
        ]);
    }

    private function loadJsonFile($filepath)
    {
        if (!file_exists($filepath)) {
            return [];
        }
        $content = file_get_contents($filepath);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = trim($content);

        $data = json_decode($content, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);
            $data = json_decode($content, true);
        }

        return $data ?? [];
    }

    public function getKota($provinsi_id)
    {
        $input = trim(urldecode((string)$provinsi_id));
        if (empty($input)) {
            return response()->json([]);
        }

        $regencies = $this->loadJsonFile(database_path('data/regencies.json'));
        $provinces = $this->loadJsonFile(database_path('data/provinces.json'));

        if (empty($regencies)) {
            return response()->json([]);
        }

        // 1. Find matching province ID
        $targetProvId = null;
        foreach ($provinces as $p) {
            $pId   = trim((string)($p['id'] ?? ''));
            $pName = trim((string)($p['name'] ?? ''));
            $pAlt  = trim((string)($p['alt_name'] ?? ''));

            if ($pId === $input || 
                (is_numeric($input) && (int)$pId === (int)$input) || 
                strcasecmp($pName, $input) === 0 || 
                ($pAlt && strcasecmp($pAlt, $input) === 0) ||
                str_contains(strtolower($pName), strtolower($input))) {
                $targetProvId = $pId;
                break;
            }
        }

        if (!$targetProvId) {
            $targetProvId = $input;
        }

        // 2. Filter regencies
        $kotas = collect($regencies)
            ->filter(function ($item) use ($targetProvId, $input) {
                $itemProvId = trim((string)($item['province_id'] ?? ''));
                $itemName   = trim((string)($item['name'] ?? ''));
                
                return $itemProvId === (string)$targetProvId
                    || (is_numeric($targetProvId) && (int)$itemProvId === (int)$targetProvId)
                    || (is_numeric($input) && (int)$itemProvId === (int)$input)
                    || strcasecmp($itemName, $input) === 0;
            })
            ->values()
            ->map(function ($item) {
                return [
                    'id'          => (string)$item['id'],
                    'provinsi_id' => (string)$item['province_id'],
                    'nama'        => ucwords(strtolower($item['name'])),
                ];
            });

        return response()->json($kotas);
    }

    public function getKecamatan($kota_id)
    {
        $input = trim(urldecode((string)$kota_id));
        if (empty($input)) {
            return response()->json([]);
        }

        $districts = $this->loadJsonFile(database_path('data/districts.json'));
        $regencies = $this->loadJsonFile(database_path('data/regencies.json'));

        if (empty($districts)) {
            return response()->json([]);
        }

        // 1. Find matching regency ID
        $targetKotaId = null;
        foreach ($regencies as $r) {
            $rId   = trim((string)($r['id'] ?? ''));
            $rName = trim((string)($r['name'] ?? ''));
            $rAlt  = trim((string)($r['alt_name'] ?? ''));

            if ($rId === $input || 
                (is_numeric($input) && (int)$rId === (int)$input) || 
                strcasecmp($rName, $input) === 0 || 
                ($rAlt && strcasecmp($rAlt, $input) === 0) ||
                str_contains(strtolower($rName), strtolower($input))) {
                $targetKotaId = $rId;
                break;
            }
        }

        if (!$targetKotaId) {
            $targetKotaId = $input;
        }

        // 2. Filter districts
        $kecamatans = collect($districts)
            ->filter(function ($item) use ($targetKotaId, $input) {
                $itemRegId = trim((string)($item['regency_id'] ?? ''));
                $itemName  = trim((string)($item['name'] ?? ''));

                return $itemRegId === (string)$targetKotaId
                    || (is_numeric($targetKotaId) && (int)$itemRegId === (int)$targetKotaId)
                    || (is_numeric($input) && (int)$itemRegId === (int)$input)
                    || strcasecmp($itemName, $input) === 0;
            })
            ->values()
            ->map(function ($item) {
                return [
                    'id'      => (string)$item['id'],
                    'kota_id' => (string)$item['regency_id'],
                    'nama'    => ucwords(strtolower($item['name'])),
                ];
            });

        return response()->json($kecamatans);
    }

    public function update_profile_admin(Request $request, $id = null)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'username'     => 'required|string|unique:users,username,' . $user->id,
                'nama_lengkap' => 'required|string',
                'provinsi_id'  => 'required',
                'kota_id'      => 'required',
                'kecamatan_id' => 'required',
            ], [
                'username.required'     => 'Username wajib diisi.',
                'username.unique'       => 'Username sudah digunakan oleh akun lain.',
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'provinsi_id.required'  => 'Provinsi wajib dipilih pada dropdown alamat.',
                'kota_id.required'      => 'Kota / Kabupaten wajib dipilih pada dropdown alamat.',
                'kecamatan_id.required' => 'Kecamatan wajib dipilih pada dropdown alamat.',
            ]);

            $imagePath = null;
            if ($request->hasFile('img_profile')) {
                $imagePath = $request->file('img_profile')->store('images', 'public');
                $user->avatar = $imagePath;
            }

            $user->username = $request->username;
            $user->nama_lengkap = $request->nama_lengkap;
            $user->save();

            // Ensure related tables exist and have corresponding rows to avoid FK constraint errors
            if (Schema::hasTable('provinsis') && $request->provinsi_id) {
                $provName = null;
                if (file_exists(database_path('data/provinces.json'))) {
                    $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
                    $found = collect($json)->firstWhere('id', (string)$request->provinsi_id);
                    if ($found) $provName = ucwords(strtolower($found['name']));
                }
                DB::table('provinsis')->updateOrInsert(
                    ['id' => $request->provinsi_id],
                    ['nama' => $provName ?? 'Provinsi ' . $request->provinsi_id, 'updated_at' => now()]
                );
            }

            if (Schema::hasTable('kotas') && $request->kota_id) {
                $kotaName = null;
                if (file_exists(database_path('data/regencies.json'))) {
                    $json = json_decode(file_get_contents(database_path('data/regencies.json')), true);
                    $found = collect($json)->firstWhere('id', (string)$request->kota_id);
                    if ($found) $kotaName = ucwords(strtolower($found['name']));
                }
                DB::table('kotas')->updateOrInsert(
                    ['id' => $request->kota_id],
                    [
                        'provinsi_id' => $request->provinsi_id,
                        'nama' => $kotaName ?? 'Kota ' . $request->kota_id,
                        'updated_at' => now()
                    ]
                );
            }

            if (Schema::hasTable('kecamatans') && $request->kecamatan_id) {
                $kecName = null;
                if (file_exists(database_path('data/districts.json'))) {
                    $json = json_decode(file_get_contents(database_path('data/districts.json')), true);
                    $found = collect($json)->firstWhere('id', (string)$request->kecamatan_id);
                    if ($found) $kecName = ucwords(strtolower($found['name']));
                }
                DB::table('kecamatans')->updateOrInsert(
                    ['id' => $request->kecamatan_id],
                    [
                        'kota_id' => $request->kota_id,
                        'nama' => $kecName ?? 'Kecamatan ' . $request->kecamatan_id,
                        'updated_at' => now()
                    ]
                );
            }

            if (Schema::hasTable('admins')) {
                $adminUpdate = [
                    'nama_lengkap'  => $request->nama_lengkap,
                    'provinsi_id'   => $request->provinsi_id,
                    'kota_id'       => $request->kota_id,
                    'kecamatan_id'  => $request->kecamatan_id,
                    'desa'          => $request->desa,
                    'kode_pos'      => $request->kode_pos,
                    'detail_alamat' => $request->detail_alamat,
                    'updated_at'    => now(),
                ];

                if ($imagePath) {
                    $adminUpdate['img_profile'] = $imagePath;
                }

                DB::table('admins')->updateOrInsert(
                    ['user_id' => $user->id],
                    $adminUpdate
                );
            }

            Notifikasi::create([
                'user_id'       => Auth::id(),
                'perusahaan_id' => null,
                'judul'         => 'Profil Berhasil Diperbarui',
                'pesan'         => 'Profil Anda berhasil diperbarui.',
                'is_read'       => 0,
                'expired_at'    => now()->addDays(7),
            ]);

            return redirect()->route('admin.profile')->with('success', 'Profil Admin berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy_profile($id = null)
    {
        $user = Auth::user();

        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        $user->avatar = null;
        $user->save();

        try {
            if (Schema::hasTable('admins')) {
                DB::table('admins')->where('user_id', $user->id)->update(['img_profile' => null]);
            }
        } catch (\Throwable $e) {
            // Table admins fallback
        }

        return redirect()->route('admin.edit.profile')->with('success', 'Profile berhasil dihapus');
    }


    //CALON KANDIDAT
    public function halCalonKandidat(Request $request)
    {
        $query = Pelamar::with('user')
            ->where('kategori', 'calon kandidat');

        // Jika ada kata kunci pencarian
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelamar', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', "%{$search}%");
                    });
            });
        }

        $pelamar = $query->orderBy('nama_pelamar')->get();

        return view('admin.pelamar.calon_kandidat.calon-kandidat', compact('pelamar'));
    }


    public function detailCalonKandidat($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        return view('admin.pelamar.calon_kandidat.detail-data-calon-kandidat', [
            'pelamar' => $pelamar
        ]);
    }



    public function updateTraining(Request $request, $id)
    {
        try {

            // VALIDASI
            $request->validate([
                'mulai_pelatihan' => 'required|date',
                'selesai_pelatihan' => 'required|date|after:mulai_pelatihan',
            ]);

            $pelamar = Pelamar::findOrFail($id);

            $pelamar->mulai_pelatihan = $request->mulai_pelatihan;
            $pelamar->selesai_pelatihan = $request->selesai_pelatihan;
            $pelamar->save();

            /* ============================================================
           NOTIFIKASI UNTUK PELAMAR
        ============================================================ */
            Notifikasi::create([
                'user_id' => $pelamar->user_id,
                'perusahaan_id' => null,
                'judul' => 'Jadwal Pelatihan Diperbarui',
                'pesan' =>
                'Silakan mengikuti pelatihan pada tanggal <b>' .
                    $request->mulai_pelatihan .
                    '</b> sampai <b>' .
                    $request->selesai_pelatihan .
                    '</b>.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            /* ============================================================
           NOTIFIKASI UNTUK ADMIN (BERHASIL)
        ============================================================ */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Berhasil Mengupdate Jadwal Pelatihan',
                'pesan' =>
                'Jadwal pelatihan untuk pelamar <b>' .
                    $pelamar->nama_pelamar .
                    '</b> telah berhasil diperbarui.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return back()->with('success', 'Jadwal pelatihan berhasil diperbarui.');
        } catch (ValidationException $e) {

            /* ============================================================
           NOTIFIKASI VALIDASI GAGAL (CONTOH: tanggal selesai < tanggal mulai)
        ============================================================ */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Validasi Jadwal Pelatihan Gagal',
                'pesan' => 'Tanggal pelatihan tidak valid. Pastikan tanggal selesai setelah tanggal mulai.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return back()->withErrors($e->errors())->with('error', 'Validasi gagal! Periksa kembali tanggal pelatihan.');
        } catch (\Exception $e) {

            /* ============================================================
           NOTIFIKASI ERROR UMUM
        ============================================================ */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Mengupdate Jadwal Pelatihan',
                'pesan' => 'Terjadi kesalahan saat menyimpan jadwal pelatihan.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return back()->with('error', 'Terjadi kesalahan! Jadwal pelatihan gagal diperbarui.');
        }
    }



    public function lulus($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $pelamar->kategori = 'kandidat aktif';
        $pelamar->save();

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => null,
            'judul' => 'Selamat! Kamu Lulus Seleksi',
            'pesan' => 'Selamat! <b>' . $pelamar->nama_pelamar . '</b> telah lulus pelatihan dan menjadi kandidat.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
            'pelamar_lowongan_id' => null,
        ]);

        return redirect()->route('admin.calon-kandidat')->with('success', 'Kandidat berhasil diluluskan.');
    }

    public function gugur($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $pelamar->kategori = 'pelamar';
        $pelamar->save();

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => null,
            'judul' => 'Status Kandidat Diperbarui',
            'pesan' => '<b>' . $pelamar->nama_pelamar . '</b> dinyatakan <span style="color:red;">gugur</span> dari proses seleksi.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
            'pelamar_lowongan_id' => null,
        ]);

        return redirect()->route('admin.non-kandidat')->with('success', 'Kandidat dinyatakan gugur.');
    }



    //NON KANDIDAT
    public function halNonKandidat(Request $request)
    {
        $query = Pelamar::with('user')
            ->where('kategori', 'pelamar');

        // Jika ada kata kunci pencarian
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelamar', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', "%{$search}%");
                    });
            });
        }

        $pelamar = $query->orderBy('nama_pelamar')->get();

        return view('admin.pelamar.non_kandidat.non-kandidat', [
            'pelamar' => $pelamar
        ]);
    }

    public function detailNonKandidat($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        return view('admin.pelamar.non_kandidat.detail-data-non-kandidat', [
            'pelamar' => $pelamar,
            'logoBase64' => $logoBase64,
            'sosmed' => $pelamar->sosmed
        ]);
    }

    //KANDIDAT 
    public function halKandidat(Request $request)
    {
        $query = Pelamar::with('user')
            ->where('kategori', 'kandidat aktif');

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($query) use ($q) {

                // cari dari relasi user (username)
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('username', 'like', "%{$q}%");
                })

                    // atau dari nama pelamar
                    ->orWhere('nama_pelamar', 'like', "%{$q}%");
            });
        }

        $pelamar = $query->get();

        return view('admin.pelamar.kandidat.pelamar', [
            'pelamar' => $pelamar,
            'q'       => $request->q
        ]);
    }



    public function detailKandidat($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        return view('admin.pelamar.kandidat.detail-data-kandidat', [
            'pelamar' => $pelamar,
            'logoBase64' => $logoBase64,
            'sosmed' => $pelamar->sosmed
        ]);
    }



    //FINANCE
    public function koinHal(Request $request)
    {
        $query = CatatanKoin::query();

        if ($request->filled('no_referensi')) {
            $query->where('no_referensi', $request->no_referensi);
        }

        $koin = $query->get();
        $noReferensiList = catatanKoin::select('no_referensi')->distinct()->pluck('no_referensi');
        return view('admin.finance.finance', [
            'koin' => $koin,
            'noReferensiList' => $noReferensiList,
            'selectedRef' => $request->no_referensi
        ]);
    }

    public function cashHal(Request $request)
    {
        $query = CatatanCash::with('user');

        if ($request->no_referensi) {
            $query->where('no_referensi', $request->no_referensi);
        }

        $cash = $query->orderBy('created_at', 'desc')->get();

        $noReferensiList = CatatanCash::whereNotNull('no_referensi')
            ->distinct()
            ->pluck('no_referensi');

        return view('admin.finance.finance-tunai', [
            'cash' => $cash,
            'noReferensiList' => $noReferensiList,
            'selectedRef' => $request->no_referensi,
        ]);
    }


    public function detail($id)
    {
        $transaksi = CatatanCash::with(['user', 'bank'])->findOrFail($id);

        return response()->json([
            'id' => $transaksi->id,
            'user' => [
                'username' => $transaksi->user->username ?? '-',
                'email' => $transaksi->user->email ?? '-',
            ],
            'bank' => [
                'nama_bank' => $transaksi->bank->nama_bank ?? '-',
                'nomor_rekening' => $transaksi->bank->no_rek ?? '-', // disesuaikan dengan field di seeder
            ],
            'sumber_dana' => $transaksi->sumberDana ?? '-', // ambil langsung dari tabel catatan_cashs
            'total' => $transaksi->total ?? 0,
            'harga' => number_format($transaksi->hargaPembayaran->harga ?? 0, 0, ',', '.'),
            'jumlah_koin' => $transaksi->hargaPembayaran->jumlah_koin ?? 0,
            'status' => ucfirst($transaksi->status),
            'created_at' => $transaksi->created_at->format('Y-m-d H:i:s'), // kirim format standar agar JS bisa parse
        ]);
    }


    public function hal_detail()
    {
        $transaksi = CatatanCash::with(['user', 'bank'])->latest()->get();
        return view('admin.finance.detail', compact('transaksi'));
    }




    //PERUSAHAAN
    public function halPerusahaan(Request $request)
    {
        $query = Perusahaan::join('users', 'perusahaans.user_id', '=', 'users.id')
            ->select('perusahaans.*', 'users.username');

        // Jika ada input pencarian username
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.username', 'like', "%{$search}%")
                    ->orWhere('perusahaans.nama_perusahaan', 'like', "%{$search}%"); // ubah ke nama_pelamar
            });
        }

        $perusahaan = $query->get();
        return view('admin.perusahaan.perusahaan', [
            'perusahaan' => $perusahaan,
            'search' => $request->search ?? ''
        ]);
    }

    public function bekukan(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        if (auth()->id() == $id) {
            // Notifikasi gagal
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Membekukan Akun',
                'pesan' => 'Anda tidak dapat membekukan akun Anda sendiri.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return response()->json(['message' => 'Anda tidak dapat membekukan akun sendiri'], 403);
        }

        try {
            $user = User::findOrFail($id);

            $user->update([
                'alasan_freeze_akun' => $request->alasan,
                'status' => 1,
            ]);

            // Notifikasi berhasil
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Akun Berhasil Dibekukan',
                'pesan' => 'Akun milik <b>' . $user->username . '</b> berhasil dibekukan.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return response()->json(['message' => 'Akun berhasil dibekukan']);
        } catch (\Exception $e) {

            // Notifikasi gagal
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Membekukan Akun',
                'pesan' => 'Terjadi kesalahan saat membekukan akun.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }


    public function aktifkan($id)
    {
        if (auth()->id() == $id) {
            // Notifikasi gagal
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Mengaktifkan Akun',
                'pesan' => 'Anda tidak dapat mengaktifkan akun Anda sendiri.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return response()->json(['message' => 'Anda tidak dapat mengubah status akun sendiri'], 403);
        }

        try {
            $user = User::findOrFail($id);

            $user->update([
                'alasan_freeze_akun' => null,
                'status' => 0,
            ]);

            // Notifikasi berhasil
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Akun Berhasil Diaktifkan',
                'pesan' => 'Akun milik <b>' . $user->username . '</b> berhasil diaktifkan kembali.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return response()->json(['message' => 'Akun berhasil diaktifkan kembali']);
        } catch (\Exception $e) {

            // Notifikasi gagal
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Mengaktifkan Akun',
                'pesan' => 'Terjadi kesalahan saat mengaktifkan akun.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }


    public function detailPerusahaan($id)
    {
        $perusahaan = Perusahaan::with(['user', 'lowonganPerusahaans'])->findOrFail($id);
        return view('admin.perusahaan.detail-data-perusahaan', [
            'perusahaan' => $perusahaan
        ]);
    }

    public function detailLowongan(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        // Pastikan lowongan milik perusahaan tersebut
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }

        return view('admin.perusahaan.view-data-lowongan', [
            'lowongan'   => $lowongan,
            'perusahaan' => $perusahaan,
        ]);
    }




    //TALENT HUNTER 
    public function talentHunterForm(Request $request)
    {
        $search = $request->search;

        $talentHunter = TalentHunter::with('perusahaan')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    // Search posisi di table talent_hunters
                    $q->where('posisi', 'like', "%{$search}%");

                    // Search nama_perusahaan di table perusahaans (relasi)
                    $q->orWhereHas('perusahaan', function ($p) use ($search) {
                        $p->where('nama_perusahaan', 'like', "%{$search}%");
                    });
                });
            })
            ->get();

        return view('admin.talent-hunter.talenthunter', [
            'talentHunter' => $talentHunter
        ]);
    }


    public function detailTalentHunter($id)
    {
        $talentHunter = TalentHunter::with('perusahaan')->findOrFail($id);
        return view('admin.talent-hunter.detail-data-talent-hunter', [
            'talentHunter' => $talentHunter
        ]);
    }




    //RECRUITMENT
    public function halPerusahaanRecruitment(Request $request)
    {
        $query = Perusahaan::join('users', 'perusahaans.user_id', '=', 'users.id')
            ->select('perusahaans.*', 'users.username');

        // Jika ada input pencarian username
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.username', 'like', "%{$search}%")
                    ->orWhere('perusahaans.nama_perusahaan', 'like', "%{$search}%"); // ubah ke nama_pelamar
            });
        }

        $perusahaan = $query->get();
        return view('admin.recruitment.perusahaan', [
            'perusahaan' => $perusahaan,
            'search' => $request->search ?? ''
        ]);
    }


    //hal rec
    public function recruitment(Request $request, $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $search = $request->search;

        $recruitments = PembeliKandidat::where('status', 'diterima')
            ->whereHas('lowonganPerusahaan', function ($q) use ($id) {
                $q->where('perusahaan_id', $id);
            })
            ->when($search, function ($q) use ($search) {

                $q->where(function ($query) use ($search) {
                    // Search username (table users)
                    $query->whereHas('pelamar.user', function ($u) use ($search) {
                        $u->where('username', 'like', "%$search%");
                    });

                    // Search nama pelamar (table pelamars)
                    $query->orWhereHas('pelamar', function ($p) use ($search) {
                        $p->where('nama_pelamar', 'like', "%$search%");
                    });

                    // Search nama lowongan_perusahaans
                    $query->orWhereHas('lowonganPerusahaan', function ($l) use ($search) {
                        $l->where('nama', 'like', "%$search%");
                    });
                });
            })
            ->with(['pelamar.user', 'lowonganPerusahaan'])
            ->get();

        return view('admin.recruitment.recruitment', [
            'perusahaan' => $perusahaan,
            'recruitments' => $recruitments,
        ]);
    }


    public function detailRecruitment($id)
    {
        $recruitment = PembeliKandidat::with([
            'pelamar.user',
            'pelamar.sosmed',
            'pelamar.pengalaman_organisasi',
            'pelamar.pengalaman_kerja',
            'pelamar.riwayat_pendidikan',
            'pelamar.alamat_pelamar',
            'pelamar.skill',
            'lowonganPerusahaan.perusahaan.alamatUtama',
            'lowonganPerusahaan.perusahaan'
        ])->findOrFail($id);

        return view('admin.recruitment.detail-recruitment', [
            'recruitment' => $recruitment
        ]);
    }


    public function destroyRecruitment($id)
    {
        //Ambil data pembelian kandidat 
        $pembelian = PembeliKandidat::with([
            'pelamar.user',
            'lowonganPerusahaan.perusahaan'
        ])->findOrFail($id);

        $user = $pembelian->pelamar->user;
        $perusahaan = $pembelian->lowonganPerusahaan->perusahaan ?? null;
        $perusahaanUser = $perusahaan->user ?? null;

        //hapus pembelian kandidat
        $pembelian->delete();

        //kirim notifikasi ke pelamar
        Notifikasi::create([
            'user_id' => $user->id,
            'perusahaan_id' => $perusahaan->id,
            'judul' => 'Status Recruitment Dibatalkan',
            'pesan' => 'Status Recruitment Anda telah dibatalkan oleh Admin.',
            'expired_at' => now()->addDays(7),
        ]);

        if ($perusahaanUser) {
            //kirim notifikasi ke perusahaan
            Notifikasi::create([
                'user_id' => $perusahaan->user->id,
                // 'perusahaan_id' => $perusahaan->id,
                'judul' => 'Status Recruitment Dibatalkan',
                'pesan' => 'Kandidat' . $pembelian->pelamar->nama_pelamar .  'telah dihapus dari daftar recruitment oleh Admin.',
                'expired_at' => now()->addDays(7),
            ]);
        }

        return redirect()->route('admin.recruitment', $perusahaan->id)->with('success', 'Recruitment berhasil dihapus & pelamar kembali menjadi kandidat biasa.');
    }




    //FILTER PROVINSI
    public function pilihProvinsi()
    {
        $provinsis = Provinsi::orderBy('nama')->get();

        return view('dashboard.pilih-provinsi', [
            'provinsis' => $provinsis,
            'selected' => session('provinsi_id')
        ]);
    }


    public function setProvinsi(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsis,id'
        ]);

        session(['provinsi_id' => $request->provinsi_id]);

        return redirect()->route('admin.dashboard')->with('success', 'Provinsi berhasil diubah!');
    }
}
