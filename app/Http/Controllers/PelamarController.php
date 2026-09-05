<?php

namespace App\Http\Controllers;

use App\Mail\KonfirmasiLamaranMail;
use App\Models\CatatanCash;
use App\Models\DaftarBank;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use App\Models\RiwayatPendidikan;
use App\Models\TipsKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PelamarController extends Controller
{

    public function pasangLowongan()
    {
        return view('non-user.pasang-lowongan');
    }

    public function talentHunter()
    {
        return view('non-user.talent-hunter');
    }

    public function bantuan()
    {
        return view('non-user.faq');
    }

    public function syaratKetentuan()
    {
        return view('layouts.syarat-dan-ketentuan');
    }



    public function detail_lowongan_non_user(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }

        $pelamar = auth()->user()?->pelamar;

        $pelamarLowongan = null;
        $statusLamaran = null;
        $isSaved = false;
        $tawaran = null;

        if ($pelamar) {
            $lamaranRecord = PelamarLowongan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $lowongan->id)
                ->where('status', '!=', 'saved')
                ->latest()
                ->first();

            $statusLamaran = $lamaranRecord?->status;

            $isSaved = PelamarLowongan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $lowongan->id)
                ->where('status', 'saved')
                ->exists();
        }

        $isExpired = false;
        if ($lowongan->batas_lamaran) {
            $isExpired = now()->greaterThan(Carbon::parse($lowongan->batas_lamaran));
        }

        $Data = LowonganPerusahaan::with('perusahaan')
            ->whereNotNull('published_at')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->latest()
            ->get();

        $lowonganLain = LowonganPerusahaan::where('id', '!=', $lowongan->id)
            ->whereNotNull('published_at')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->latest()
            ->take(3)
            ->get();

        return view('non-user.lowongan-detail', [
            'data'          => $lowongan,
            'Data'          => $Data,
            'isSaved'       => $isSaved,
            'lowonganLain'  => $lowonganLain,
            'tawaran'       => $tawaran,
            'isExpired'     => $isExpired,
            'statusLamaran' => $statusLamaran,
        ]);
    }

    public function detail_wongan_non_userShare($slug)
    {
        $lowongan = LowonganPerusahaan::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        $perusahaan = $lowongan->perusahaan;

        return $this->detail_lowongan_non_user($perusahaan->slug, $lowongan->slug);
    }





    public function index(Request $request)
    {
        // Ambil kategori dari query string
        $kategori = $request->query('kategori');

        // Jika kategori ada → simpan, filter, lalu redirect ke URL bersih
        if ($kategori) {
            return redirect()
                ->to('/pelamar/home')
                ->with('kategori_filter', $kategori);
        }

        // Ambil kategori dari session ONLY untuk 1x
        $kategori = session()->pull('kategori_filter');

        $KategoriList = LowonganPerusahaan::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        if ($KategoriList->isEmpty()) {
            $KategoriList = collect(['IT & Software', 'Marketing', 'Finance', 'Desain & Kreatif', 'Operasional', 'Sales']);
        }

        $Data = LowonganPerusahaan::with('perusahaan')
            ->whereNotNull('published_at')
            ->where('status', '!=', 'tutup')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })

            ->when($kategori, function ($q) use ($KategoriList, $kategori) {
                if ($KategoriList->contains($kategori)) {
                    $q->where('kategori', $kategori);
                }
            })

            /* ===============================
     | PRIORITAS GRUP
     =============================== */
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NOT NULL THEN 0
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NULL THEN 1
            WHEN boosted_until IS NULL AND rekomendasi IS NOT NULL THEN 2
            ELSE 3
        END
    ")

            /* ===============================
     | URUTAN DALAM GRUP
     =============================== */

            // -- Boost + Rekomendasi → BOOST TERBARU DI ATAS
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NOT NULL
            THEN boosted_until
        END DESC
    ")

            // -- Boost saja → BOOST TERBARU DI ATAS
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NULL
            THEN boosted_until
        END DESC
    ")

            // -- Rekomendasi saja → PALING LAMA DI ATAS
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NULL AND rekomendasi IS NOT NULL
            THEN rekomendasi
        END ASC
    ")

            // -- Biasa → TERBARU DI ATAS
            ->orderBy('created_at', 'DESC')

            ->get();


        $jenisList = LowonganPerusahaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '!=', '')
            ->distinct()
            ->pluck('jenis');

        return view('non-user.home', [
            "Data" => $Data,
            "KategoriList" => $KategoriList,
            "kategori" => $kategori,
            "jenisList" => $jenisList,
        ]);
    }




    //SIMPAN LOWONGAN 
    public function store(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id',
        ]);

        $pelamar = Auth::user()->pelamar;

        $cek = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $request->lowongan_id)
            ->first();

        if ($cek) {
            if ($cek->status === 'saved') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Lowongan sudah ada di daftar simpan.']);
                }
                return back()->with('error', 'Lowongan sudah ada di daftar simpan.');
            }
            $cek->update(['status' => 'saved']);
        } else {
            PelamarLowongan::create([
                'pelamar_id'  => $pelamar->id,
                'lowongan_id' => $request->lowongan_id,
                'status'      => 'saved',
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lowongan berhasil disimpan.']);
        }

        return back()->with('success', 'Lowongan berhasil disimpan.');
    }

    public function destroy(Request $request, $id)
    {
        $pelamar = Auth::user()->pelamar;

        $simpan = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $id)
            ->where('status', 'saved')
            ->first();

        if (!$simpan) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Lowongan tidak ditemukan di daftar simpan.'], 404);
            }
            return back()->with('error', 'Lowongan tidak ditemukan di daftar simpan.');
        }

        $simpan->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lowongan berhasil dihapus dari daftar simpan.']);
        }

        return back()->with('success', 'Lowongan berhasil dihapus dari daftar simpan.');
    }

    public function lowongansimpanform()
    {
        $user = Auth::user();
        $pelamar = $user?->pelamar;

        if (!$pelamar) {
            $simpanlowongan = collect();
            return view('non-user.lowongan-tersimpan', compact('simpanlowongan'));
        }

        $simpanlowongan = PelamarLowongan::with('lowongan.perusahaan')
            ->where('pelamar_id', $pelamar->id)
            ->where('status', 'saved')
            ->whereHas('lowongan', function ($q) {
                $q->whereNotNull('published_at')
                    ->where(function ($q2) {
                        $q2->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            })
            ->latest()
            ->get();

        return view('non-user.lowongan-tersimpan', compact('simpanlowongan'));
    }

    public function lamaranKerja()
    {
        $user = Auth::user();
        $pelamar = $user?->pelamar;
        $lamaranList = collect();

        if ($pelamar) {
            $lamaranList = PelamarLowongan::with('lowongan_perusahaan.perusahaan')
                ->where('pelamar_id', $pelamar->id)
                ->where('status', '!=', 'saved')
                ->latest()
                ->get();
        }

        return view('non-user.lamaran-kerja', compact('lamaranList'));
    }


    // RIWAYAT PENDIDIKAN
    public function storependidikan(Request $request)
    {
        $valid = $request->validate([
            'pendidikan' => 'required',
            'jurusan' => 'nullable',
            'asal_pendidikan' => 'nullable',
            'tahun_awal' => 'nullable|digits:4|integer',
            'tahun_akhir' => 'nullable|gte:tahun_awal|digits:4|integer',
        ]);

        $valid['pelamar_id'] = Auth::user()->pelamar->id;

        RiwayatPendidikan::create($valid);
        return redirect()->route('profile.index')->with('success', 'Pendidikan berhasil disimpan');
    }

    public function updatependidikan(Request $request, RiwayatPendidikan $riwayatpendidikan)
    {
        $valid = $request->validate([
            'pendidikan' => 'required',
            'jurusan' => 'nullable',
            'asal_pendidikan' => 'nullable',
            'tahun_awal' => 'nullable|digits:4|integer',
            'tahun_akhir' => 'nullable|gte:tahun_awal|digits:4|integer',
        ]);

        $valid['pelamar_id'] = Auth::user()->pelamar->id;

        $riwayatpendidikan->update($valid);
        return redirect()->route('profile.index')->with('success', 'Pendidikan berhasil diperbarui');
    }

    public function editpendidikan(RiwayatPendidikan $riwayatpendidikan)
    {
        return view('non-user.profile.pendidikan.edit', ['DT' => $riwayatpendidikan]);
    }

    public function destroypendidikan(RiwayatPendidikan $riwayatpendidikan)
    {
        $riwayatpendidikan->delete();
        return redirect()->back()->with('success', 'Pendidikan berhasil dihapus');
    }


    // RIWAYAT PENDIDIKAN SUper ADmin
    public function storependidikanSuper(Request $request)
    {
        $valid = $request->validate([
            'pendidikan' => 'required',
            'jurusan' => 'nullable',
            'asal_pendidikan' => 'nullable',
            'tahun_awal' => 'nullable|digits:4|integer',
            'tahun_akhir' => 'nullable|gte:tahun_awal|digits:4|integer',
        ]);
        $pelamar_id = session('pelamar_terakhir_id');

        if (!$pelamar_id) {
            return back()->with('error', 'Pelamar belum dibuat. Harap buat pelamar terlebih dahulu sebelum menambahkan pendidikan.');
        }

        $valid['pelamar_id'] = $pelamar_id;

        RiwayatPendidikan::create($valid);

        $pelamar = Pelamar::find($pelamar_id);


        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        return redirect()->route('superadmin.pelamar.create', ['kategori' => $kategori])
            ->with('success', 'Organisasi berhasil disimpan');
    }

    public function updatependidikanSuper(Request $request, ?RiwayatPendidikan $riwayatpendidikan = null)
    {

        $valid = $request->validate([
            'pelamar_id' => 'required|exists:pelamars,id',
            'pendidikan' => 'required',
            'jurusan' => 'nullable',
            'asal_pendidikan' => 'nullable',
            'tahun_awal' => 'nullable|digits:4|integer',
            'tahun_akhir' => 'nullable|gte:tahun_awal|digits:4|integer',
        ]);

        // Tidak perlu ambil dari session, karena sudah dari request
        $pelamar_id = $valid['pelamar_id'];

        if ($riwayatpendidikan && $riwayatpendidikan->exists) {
            $riwayatpendidikan->update($valid);
        } else {
            $riwayatpendidikan = RiwayatPendidikan::create($valid);
        }

        $pelamar = Pelamar::find($pelamar_id);

        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        return redirect()->route('superadmin.pelamar.edit', [
            'kategori' => $kategori,
            'id' => $pelamar_id
        ])->with('success', 'Data pendidikan berhasil disimpan.');
    }



    public function editpendidikanSuper(RiwayatPendidikan $riwayatpendidikan)
    {
        return view('super_admin.pelamar.modal.edit.edit_pendidikan', ['DT' => $riwayatpendidikan]);
    }

    public function destroypendidikanSuper(RiwayatPendidikan $riwayatpendidikan)
    {
        $riwayatpendidikan->delete();
        return redirect()->back()->with('success', 'Pendidikan berhasil dihapus');
    }

    // LAMARAN PELAMAR
    public function lamar_cepat(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->firstOrFail();

        $alreadyApplied = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $request->lowongan_id)
            ->where('status', '!=', 'saved')
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Anda sudah mengirimkan lamaran untuk lowongan ini.');
        }

        $pelamar->lowongans()->attach($request->lowongan_id, [
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Lamaran berhasil dikirim');
    }

    // FORM KONFIRMASI
    public function konfirmasi_hal(PelamarLowongan $pelamarlowongan)
    {
        return view('perusahaan.pelamar.terima-pelamar', [
            "data" => $pelamarlowongan
        ]);
    }

    // SIMPAN INPUTAN FORM KE SESSION
    public function konfirmasi_simpan(Request $request, PelamarLowongan $pelamarlowongan)
    {
        // dd($request->all());
        $val = $request->validate([
            'tanggal'   => 'required|date',
            'jam'       => 'required|integer|min:0|max:23',
            'menit'     => 'required|integer|min:0|max:59',
            'tempat'    => 'required|string|max:255',
            'catatan'   => 'nullable|string',
            'gmaps_url' => [
                'required',
                'string',
                'max:1000',
                function ($attr, $value, $fail) {
                    if (!str_contains($value, 'google.com/maps') && !str_contains($value, 'maps.app.goo.gl')) {
                        $fail('Link harus berasal dari Google Maps.');
                    }
                }
            ],
        ]);

        // ======================
        // GABUNG JAM & MENIT
        // ======================
        $val['waktu'] =
            str_pad($val['jam'], 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($val['menit'], 2, '0', STR_PAD_LEFT);

        unset($val['jam'], $val['menit']);

        // ======================
        // SIMPAN KE SESSION
        // ======================
        session(['konfirmasi' => $val]);

        // ======================
        // SIMPAN KE DATABASE
        // ======================
        $pelamarlowongan->update([
            'gmaps_url' => $val['gmaps_url'],
        ]);

        return redirect()->route('pelamar.detail', $pelamarlowongan->id);
    }






    // PREVIEW
    public function preview(PelamarLowongan $pelamarlowongan)
    {
        $konfirmasi = session('konfirmasi');

        if (!$konfirmasi) {
            return redirect()->route('pelamar.konfirmasi', $pelamarlowongan->id)
                ->with('error', 'Isi form konfirmasi terlebih dahulu.');
        }

        return view('perusahaan.pelamar.konfirmasi-terkirim', [
            "data"       => $pelamarlowongan,
            "konfirmasi" => $konfirmasi,
        ]);
    }




    // KIRIM EMAIL + BUAT NOTIFIKASI
    public function kirim(PelamarLowongan $pelamarlowongan)
    {
        $pelamar = $pelamarlowongan->pelamar;
        $konfirmasi = session('konfirmasi');

        $mapsUrl = null;
        if ($pelamarlowongan->latitude && $pelamarlowongan->longitude) {
            $mapsUrl = "https://www.google.com/maps?q={$pelamarlowongan->latitude},{$pelamarlowongan->longitude}";
        }


        if (!$konfirmasi) {
            return redirect()->route('pelamar.konfirmasi', $pelamarlowongan->id)
                ->with('error', 'Data konfirmasi tidak ditemukan.');
        }

        $expiredAt = now()->addDays(30);

        // Update status + expired_at
        $pelamarlowongan->update([
            "status"      => "diterima",
            "expired_at"  => $expiredAt,
        ]);

        try {
            Mail::to($pelamar->user->email)
                ->send(new KonfirmasiLamaranMail(
                    $pelamar,
                    $pelamarlowongan->lowongan_perusahaan,
                    $konfirmasi,
                    $pelamarlowongan,
                    $mapsUrl
                ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim email konfirmasi lamaran: ' . $e->getMessage());
        }

        $statusText = $pelamarlowongan->status === 'diterima' ? 'Diterima' : 'Ditolak';
        $statusColor = $statusText === 'Diterima' ? 'green' : 'red';

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => $pelamarlowongan->lowongan_perusahaan->perusahaan_id,
            'pelamar_lowongan_id' => $pelamarlowongan->id,
            'judul'   => "Lamaran {$statusText}",
            'pesan'   => "Lamaran yang anda ajukan ke <b>{$pelamarlowongan->lowongan_perusahaan->perusahaan->nama_perusahaan}</b> 
                  divisi <b>{$pelamarlowongan->lowongan_perusahaan->nama}</b> 
                  <span style='color:{$statusColor}; font-weight:bold;'>{$statusText}</span>. 
                  Masa berlaku lamaran sampai tanggal <b>{$expiredAt->format('d M Y')}</b>.",
            'expired_at' => now()->addDays(7),

        ]);

        session()->forget('konfirmasi');

        return redirect()->route('perusahaan.dashboard', [
            'lowongan' => $pelamarlowongan->lowongan_perusahaan->slug
        ])->with('success', 'Lamaran diterima, email konfirmasi & notifikasi sudah dikirim.');
    }

    public function tolak(PelamarLowongan $pelamarlowongan)
    {
        $pelamar = $pelamarlowongan->pelamar;

        $pelamarlowongan->update([
            'status' => 'ditolak',
            'expired_at' => null,
        ]);

        try {
            Mail::to($pelamar->user->email)
                ->send(new KonfirmasiLamaranMail(
                    $pelamar,
                    $pelamarlowongan->lowongan_perusahaan,
                    null,
                    $pelamarlowongan
                ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim email penolakan lamaran: ' . $e->getMessage());
        }

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'pelamar_lowongan_id' => $pelamarlowongan->id,
            'perusahaan_id' => $pelamarlowongan->lowongan_perusahaan->perusahaan_id,
            'judul'   => "Lamaran Ditolak",
            'pesan'   => "Lamaran anda ke <b>{$pelamarlowongan->lowongan_perusahaan->perusahaan->nama_perusahaan}</b> 
              divisi <b>{$pelamarlowongan->lowongan_perusahaan->nama}</b> 
              <span style='color:red; font-weight:bold;'>Ditolak</span>. 
              Terima kasih telah melamar, semoga sukses di kesempatan berikutnya.",
            'expired_at' => now()->addDays(7),
        ]);

        session()->forget('konfirmasi');

        return redirect()->route('perusahaan.pelamar', [
            'lowongan' => $pelamarlowongan->lowongan_perusahaan->slug
        ])->with('success', 'Lamaran ditolak, email & notifikasi sudah dikirim.');
    }




    // NOTIFIKASI
    public function baca($id)
    {
        $notif = Notifikasi::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notif->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }



    public function bacaSemua()
    {
        $userId = auth()->id();

        $updated = Notifikasi::where('user_id', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        dd($userId, $updated, Notifikasi::where('user_id', $userId)->get());
    }


    public function hapus($id)
    {
        $notif = Notifikasi::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notif->delete();

        return response()->json(['success' => true]);
    }

    public function hapusSemua()
    {
        $userId = auth()->id();

        $deleted = Notifikasi::where('user_id', $userId)
            ->delete();

        return response()->json([
            'success' => true,
            'deleted' => $deleted
        ]);
    }

    public function hapusSemuaBaca()
    {
        $userId = auth()->id();

        // Hapus hanya yang is_read = 1
        $deleted = Notifikasi::where('user_id', $userId)
            ->where('is_read', 1)
            ->delete();

        return response()->json([
            'success' => true,
            'deleted' => $deleted
        ]);
    }





    //TIPS KERJA
    public function tips_kerja(Request $request)
    {
        $kategori = $request->query('kategori');

        $query = TipsKerja::where('status', 'terbit');

        if ($kategori && $kategori !== 'Semua') {
            $query->where('kategori', $kategori);
        }

        $head = (clone $query)->orderBy('created_at', 'desc')->first();

        if (!$head && $kategori) {
            $head = TipsKerja::where('status', 'terbit')->orderBy('created_at', 'desc')->first();
        }

        $others = (clone $query)
            ->when($head, function ($q) use ($head) {
                return $q->where('id', '!=', $head->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('non-user.tips-kerja', [
            "head"   => $head,
            "others" => $others
        ]);
    }

    public function detail(TipsKerja $tips)
    {
        $related = TipsKerja::where('id', '!=', $tips->id)
            ->where('status', 'terbit')
            ->latest()
            ->take(3)
            ->get();

        // $tips->increment('views');

        return view('non-user.tips-kerja1', [
            'artikel' => $tips,
            'related' => $related,
        ]);
    }
    //HAL DAFTAR KANDIDAT
    public function daftar_kandidat()
    {
        $user = auth()->user();
        $isKandidatAktif = false;
        $transaksiPending = null;

        if ($user) {
            $isKandidatAktif = in_array(strtolower($user->pelamar->kategori ?? ''), ['kandidat aktif', 'kandidat', 'calon kandidat']);
            
            $transaksiPending = CatatanCash::where('user_id', $user->id)
                ->where('pesanan', 'Pendaftaran Kandidat')
                ->whereIn('status', ['pending', 'menunggu_verifikasi'])
                ->latest()
                ->first();
        }

        $divisis = collect([
            (object)['id' => 1, 'divisi' => 'Teknologi Informasi & Software'],
            (object)['id' => 2, 'divisi' => 'Marketing & Komunikasi'],
            (object)['id' => 3, 'divisi' => 'Keuangan & Akuntansi'],
            (object)['id' => 4, 'divisi' => 'Desain Grafis & Multimedia'],
            (object)['id' => 5, 'divisi' => 'Administrasi & HRD'],
            (object)['id' => 6, 'divisi' => 'Penjualan & Bisnis'],
        ]);

        return view('non-user.daftar-kandidat', [
            "divisis"          => $divisis,
            'daftarBank'       => DaftarBank::all(),
            'isKandidatAktif'  => $isKandidatAktif,
            'transaksiPending' => $transaksiPending,
        ]);
    }

    public function transaksi($id)
    {
        $transaksi = CatatanCash::with(['bank'])->findOrFail($id);
        return view('kandidat.transaksi-tf-bank', [
            "transaksi" => $transaksi,
            'daftarBank' => DaftarBank::all(),
        ]);
    }

    public function uploadBukti(Request $request, $id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti-transfer', 'public');
            $transaksi->update([
                'bukti' => $path,
                'status' => 'menunggu_verifikasi',
            ]);
        }

        return redirect()->route('kandidat.transaksi', $transaksi->id)
            ->with('success', 'Bukti transfer berhasil diupload.');
    }

    public function storePendaftaran(Request $request)
    {
        $user = auth()->user();

        // 1. Cek apakah pelamar sudah menjadi kandidat aktif
        if ($user && $user->pelamar && in_array(strtolower($user->pelamar->kategori ?? ''), ['kandidat aktif', 'kandidat'])) {
            return redirect()->route('pelamar.daftar-kandidat')
                ->with('error', 'Anda sudah terdaftar sebagai Kandidat Aktif.');
        }

        // 2. Cek apakah pelamar sudah memiliki transaksi pendaftaran kandidat yang pending
        $transaksiExist = CatatanCash::where('user_id', $user->id)
            ->where('pesanan', 'Pendaftaran Kandidat')
            ->whereIn('status', ['pending', 'menunggu_verifikasi'])
            ->latest()
            ->first();

        if ($transaksiExist) {
            return redirect()->route('kandidat.transaksi', $transaksiExist->id)
                ->with('error', 'Anda sudah mendaftar sebagai Kandidat. Silakan selesaikan transaksi pembayaran Anda.');
        }

        $request->validate([
            'divisi' => 'required|string',
            'daftar_bank_id' => 'required|exists:daftar_bank,id',
        ]);

        // Update divisi pelamar
        if ($user->pelamar) {
            $user->pelamar->update(['divisi' => $request->divisi]);
        }

        $bank = DaftarBank::findOrFail($request->daftar_bank_id);
        $totalHarga = 200000; // Standar biaya komitmen pendaftaran kandidat AK

        // Sumber dana
        $sumberDana = strtolower($bank->nama_bank) === 'qris'
            ? 'Qris'
            : 'Transfer Bank';

        $dari = $user->pelamar->nama_pelamar ?? $user->username;

        // Buat transaksi
        $transaksi = CatatanCash::create([
            'user_id'        => $user->id,
            'daftar_bank_id' => $request->daftar_bank_id,
            'no_referensi'   => 'INV' . strtoupper(uniqid()),
            'pesanan'        => 'Pendaftaran Kandidat',
            'dari'           => $dari,
            'sumberDana'     => $sumberDana,
            'total'          => $totalHarga,
            'status'         => 'pending',
            'expired_at'     => now()->addHours(24),
        ]);

        return redirect()->route('kandidat.transaksi', $transaksi->id);
    }





    //SEARCH LOWONGAN
    public function searchLowongan(Request $request)
    {
        $previous = url()->previous();

        // Jangan simpan jika previous URL nya adalah URL search
        if (!str_contains($previous, '/search')) {
            session()->put('last_non_search_url', $previous);
        }

        $posisi = $request->posisi;
        $lokasi = $request->lokasi;
        $kategori = $request->kategori;
        $jenis    = $request->jenis;

        $adaPencarian = $posisi || $lokasi || $kategori || $jenis;

        $jenisList = LowonganPerusahaan::query()
            ->whereNotNull('jenis')
            ->distinct()
            ->pluck('jenis');


        // Ambil Kategori untuk menghindari error di Blade
        $KategoriList = LowonganPerusahaan::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        if ($KategoriList->isEmpty()) {
            $KategoriList = collect(['IT & Software', 'Marketing', 'Finance', 'Desain & Kreatif', 'Operasional', 'Sales']);
        }
        // $kategori = session()->get('kategori_filter'); // jaga konsistensi filter kategori

        // Jika posisi & lokasi kosong → tampilkan normal saja
        if (!$adaPencarian) {

            $lowongan = LowonganPerusahaan::with(['perusahaan'])
                ->whereNotNull('published_at')
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                        ->orWhere('expired_at', '>', now());
                })
                ->orderByRaw("
                    CASE
                        WHEN boosted_until IS NOT NULL AND rekomendasi IS NOT NULL THEN 0
                        WHEN boosted_until IS NOT NULL AND rekomendasi IS NULL THEN 1
                        WHEN boosted_until IS NULL AND rekomendasi IS NOT NULL THEN 2
                        ELSE 3
                    END
                ")
                ->latest('published_at')
                ->paginate(12);

            return view('non-user.home', [
                'Data'         => $lowongan,
                'lowongan'     => $lowongan,
                'posisi'       => null,
                'lokasi'       => null,
                'kategori'     => null,
                'jenis'        => null,
                'riwayat'      => session()->get('riwayat_full', []),
                'KategoriList' => $KategoriList,
                'jenisList'    => $jenisList,
                "adaPencarian" => $adaPencarian,
            ]);
        }

        // Cari lowongan
        $lowongan = LowonganPerusahaan::query()
            ->with(['perusahaan'])
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('kategori', $kategori);
            })
            ->when($jenis, function ($q) use ($jenis) {
                $q->where('jenis', $jenis);
            })
            ->when($posisi, function ($q) use ($posisi) {
                $q->where(function ($q2) use ($posisi) {
                    $q2->where('nama', 'like', "%$posisi%")
                        ->orWhere('deskripsi', 'like', "%$posisi%");
                });
            })
            ->when($lokasi, function ($q) use ($lokasi) {
                $q->where(function ($q2) use ($lokasi) {
                    $q2->where('alamat', 'like', "%$lokasi%")
                       ->orWhereHas('perusahaan', function ($p) use ($lokasi) {
                           $p->where('alamat', 'like', "%$lokasi%")
                             ->orWhere('kota', 'like', "%$lokasi%")
                             ->orWhere('provinsi', 'like', "%$lokasi%");
                       });
                });
            })
            ->whereNotNull('published_at')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->latest('published_at')
            ->paginate(12);


        $riwayat = session()->get('riwayat_full', []);

        if ($adaPencarian) {

            $riwayat = collect($riwayat)
                ->reject(function ($item) use ($posisi, $lokasi, $kategori, $jenis) {
                    return ($item['posisi'] ?? null) === $posisi &&
                        ($item['lokasi'] ?? null) === $lokasi &&
                        ($item['kategori'] ?? null) === $kategori &&
                        ($item['jenis'] ?? null) === $jenis;
                })
                ->values()
                ->toArray();


            // HANYA SIMPAN JIKA ADA HASIL
            if ($lowongan->count() > 0) {
                array_unshift($riwayat, [
                    'posisi'        => $posisi,
                    'lokasi'        => $lokasi,
                    'kategori'      => $kategori,
                    'jenis'         => $jenis,
                    'lowongan_ids'  => $lowongan->pluck('id')->toArray(),
                ]);
            }

            $riwayat = array_slice($riwayat, 0, 6);
            session()->put('riwayat_full', $riwayat);
        }



        return view('non-user.home', [
            'Data' => $lowongan,
            'lowongan' => $lowongan,
            'posisi' => $posisi,
            'lokasi' => $lokasi,
            'riwayat' => $riwayat,
            'KategoriList' => $KategoriList,
            'kategori' => $kategori,
            'jenis' => $jenis,
            'jenisList' => $jenisList,
            "adaPencarian" => $adaPencarian,
        ]);
    }

    //hapus search riwayat
    public function resetRiwayat()
    {
        session()->forget('riwayat_full');
        session()->forget('riwayat_search');

        $lastUrl = session()->get('last_non_search_url', route('beranda')); // fallback ke home

        return redirect($lastUrl)->with('success', 'Riwayat pencarian berhasil direset.');
    }




    //Transaksi
    public function transaksiPendaftaranKandidat()
    {
        $user = auth()->user();

        $transaksi = CatatanCash::where('user_id', $user->id)
            ->where('pesanan', 'Pendaftaran Kandidat')
            ->with(['bank'])
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('non-user.transaksi.transaksi-kosong', [
            'transaksi' => $transaksi
        ]);
    }


    //GANTI PW
    public function updatePassword(Request $request)
    {
        // Validasi biasa
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:3',
            'new_password_confirmation' => 'required'
        ]);

        $user = $request->user();

        // ==== CEK PASSWORD BARU & KONFIRMASI ====
        if ($request->new_password !== $request->new_password_confirmation) {

            // Simpan notifikasi gagal
            Notifikasi::create([
                'user_id'    => $user->id,
                'perusahaan_id' => null,
                'judul'      => 'Gagal Mengubah Password',
                'pesan'      => 'Password baru dan konfirmasi password tidak sama.',
                'is_read'    => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return back()->with('error', 'Password baru dan konfirmasi password tidak cocok.');
        }

        // ==== CEK PASSWORD LAMA ====
        if (!Hash::check($request->old_password, $user->password)) {

            Notifikasi::create([
                'user_id'    => $user->id,
                'perusahaan_id' => null,
                'judul'      => 'Gagal Mengubah Password',
                'pesan'      => 'Password gagal diubah karena password lama tidak sesuai.',
                'is_read'    => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return back()->with('error', 'Password lama salah.');
        }

        // ==== UPDATE PASSWORD ====
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Notifikasi berhasil
        Notifikasi::create([
            'user_id'    => $user->id,
            'perusahaan_id' => null,
            'judul'      => 'Password Berhasil Diubah',
            'pesan'      => 'Password akun Anda berhasil diperbarui.',
            'is_read'    => 0,
            'expired_at' => now()->addDays(7),
            'pelamar_lowongan_id' => null,
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
