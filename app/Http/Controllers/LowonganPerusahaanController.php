<?php

namespace App\Http\Controllers;

use App\Models\CatatanKoin;
use App\Models\Category;
use App\Models\Hargakoin;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\PaketLowongan;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LowonganPerusahaanController extends Controller
{
    public function index(Request $request)
    {
        //ambil semua paket lowongan
        $pakets = PaketLowongan::all();

        // Ambil semua jenis lowongan unik
        $jenisLowongan = LowonganPerusahaan::select('jenis')->distinct()->pluck('jenis');

        $query = LowonganPerusahaan::where('perusahaan_id', auth()->user()->perusahaan->id);

        if ($request->paket) {
            $query->where('paket_id', $request->paket);
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        // Ambil lowongan yang sesuai dengan filter
        $lowongans = $query->latest()->get();

        $hargaBoost = HargaKoin::where('nama', 'Boost Lowongan')->value('harga');

        return view('perusahaan.lowongan-saya.lowongan-kosong', [
            "Data" => $lowongans,
            'lowongans' => $lowongans,
            'pakets' => $pakets,
            'jenisLowongan' => $jenisLowongan,
            'hargaBoost' => $hargaBoost,
        ]);
    }


    public function paketform()
    {
        $perusahaan = Auth::user()->perusahaan;

        // Ambil semua paket (Gold, Silver, Bronze)
        $pakets = PaketLowongan::whereIn('nama', ['Gold', 'Silver', 'Bronze'])->get();

        // Ambil semua harga berdasarkan nama paket
        $hargaKoins = HargaKoin::whereIn('nama', [
            'Pasang Lowongan Bronze',
            'Pasang Lowongan Silver',
            'Pasang Lowongan Gold'
        ])->get()->keyBy('nama');

        foreach ($pakets as $paket) {
            $namaHarga = 'Pasang Lowongan ' . $paket->nama;
            $paket->harga = $hargaKoins[$namaHarga]->harga ?? 0;
        }

        return view('perusahaan.pasang-lowongan', compact('perusahaan', 'pakets'));
    }





    public function createForm()
    {
        $pakets = PaketLowongan::all();
        $categories = Category::all();
        return view('perusahaan.lowongan-saya.tambah-lowongan', compact('pakets', 'categories'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'nama'             => 'required|string|max:255',
            'alamat'           => 'required|string|max:255',
            'jenis'            => 'required|string',
            'kategori'         => 'required|string',
            'label_gaji'       => 'required|string|max:100',
            'benefit'          => 'required|string',
            'gaji_awal'        => 'required|numeric|min:0',
            'gaji_akhir'       => 'required|numeric|min:0',
            'deskripsi'        => 'required|string',
            'tanggung_jawab'   => 'required|string',
            'syarat_pekerjaan' => 'required|string',
            'batas_lamaran'    => 'required|date',
        ], [
            'nama.required'             => 'Judul lowongan wajib diisi.',
            'alamat.required'           => 'Alamat lowongan wajib diisi.',
            'jenis.required'            => 'Jenis lowongan wajib dipilih.',
            'kategori.required'         => 'Kategori lowongan wajib dipilih.',
            'label_gaji.required'       => 'Label gaji wajib diisi.',
            'benefit.required'          => 'Benefit lowongan wajib diisi.',
            'gaji_awal.required'        => 'Gaji minimal wajib diisi.',
            'gaji_akhir.required'       => 'Gaji maksimal wajib diisi.',
            'deskripsi.required'        => 'Deskripsi lowongan wajib diisi.',
            'tanggung_jawab.required'   => 'Tanggung jawab wajib diisi.',
            'syarat_pekerjaan.required' => 'Pendidikan/Syarat pekerjaan wajib dipilih.',
            'batas_lamaran.required'    => 'Batas waktu lamaran wajib diisi.',
        ]);


        $valid['perusahaan_id'] = Auth::user()->perusahaan->id;
        $valid['slug'] = Str::slug($request->nama . '-' . time());
        // $valid['tanggung_jawab'] = Auth::user()->perusahaan->nama_perusahaan;

        LowonganPerusahaan::create($valid);
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Lowongan berhasil ditambahkan!');
    }

    public function show(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        $lowonganLainnya = LowonganPerusahaan::where('perusahaan_id', $lowongan->perusahaan_id)
            ->where('id', '!=', $lowongan->id)
            ->latest()
            ->take(5)
            ->get();

        // Tambahan keamanan → pastikan lowongan ini milik perusahaan yang ada di URL
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }


        $isBoostActive = !is_null($lowongan->boosted_until);

        // optional: waktu terakhir boost (untuk info / sorting / badge)
        $boostedAt = $lowongan->boosted_until
            ? \Carbon\Carbon::parse($lowongan->boosted_until)
            : null;



        // Jika Data dipakai untuk sidebar "lowongan lain dari perusahaan ini":
        return view('perusahaan.lowongan-saya.detail-lowongan', [
            "data"           => $lowongan,
            "Data"           => LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
                                    ->where('id', '!=', $lowongan->id)
                                    ->latest()
                                    ->take(5)   // ← batasi hanya 5
                                    ->get(),
            "lowonganLainnya" => $lowonganLainnya,
            "isBoostActive"   => $isBoostActive,
            "boostedAt"       => $boostedAt,
        ]);
    }

    public function edit(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        // Tambahan keamanan pastikan lowongan ini milik perusahaan yang ada di URL
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }
        $categories = Category::all();
        return view('perusahaan.lowongan-saya.edit', [
            "data" => $lowongan,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, LowonganPerusahaan $lowongan)
    {
        $valid = $request->validate([
            'nama' => 'nullable|string|max:255',
            'jenis' => 'nullable|string',
            'gaji_awal' => 'nullable|numeric',
            'gaji_akhir' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'kategori' => 'nullable|string',
            'batas_lamaran' => 'nullable|date',
            'deskripsi' => 'nullable|string',
            'syarat_pekerjaan' => 'nullable|string',
            'benefit' => 'nullable|string',
            'label_gaji' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
        ]);

        $valid['perusahaan_id'] = Auth::user()->perusahaan->id;
        $lowongan->update($valid);
        return redirect()->route('lowongan.detail', [
            'perusahaan' => $lowongan->perusahaan->slug,
            'lowongan'   => $lowongan->slug,
        ])->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(LowonganPerusahaan $lowongan)
    {
        $lowongan->delete();
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Lowongan berhasil dihapus.');
    }

    public function publish(Request $request, LowonganPerusahaan $lowongan)
    {
        $perusahaan = Auth::user()->perusahaan;
        $user = Auth::user();

        // Pastikan lowongan milik perusahaan login
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            return redirect()->back()->with('error', 'Lowongan tidak valid.');
        }

        if ($lowongan->expired_at && $lowongan->expired_at > now()) {
            return redirect()->back()->with('error', 'Lowongan ini masih aktif dan belum bisa dipublish ulang.');
        }

        // Pastikan lowongan punya paket
        if (!$lowongan->paket_id) {
            return redirect()->route('paket.form')
                ->with('error', 'Lowongan ini belum memiliki paket. Silakan beli paket dulu.');
        }

        $paket = PaketLowongan::find($lowongan->paket_id);
        if (!$paket) {
            return redirect()->back()->with('error', 'Paket tidak ditemukan.');
        }

        if ($lowongan->expired_at && $lowongan->expired_at < now() && $lowongan->published_at !== null) {
            $lowongan->update(['paket_id' => null]);

            return redirect()
                ->route('paket.form')
                ->with('error', 'Masa aktif paket sebelumnya telah habis. Silakan beli paket baru untuk mempublish ulang lowongan ini.');
        }


        $now = now();

        // JIKA LOWONGAN MASIH AKTIF → TAMBAH WAKTU
        if ($lowongan->expired_at && $lowongan->expired_at > $now) {

            $lowongan->update([
                'expired_at' => $lowongan->expired_at->addDays($paket->batas_listing),
            ]);
        } else {
            // JIKA BELUM PERNAH / SUDAH EXPIRED
            $lowongan->update([
                'published_at' => $now,
                'expired_at'   => $now->addDays($paket->batas_listing),
            ]);
        }


        return redirect()->route('lowongan.saya.perusahaan')
            ->with('success', 'Lowongan berhasil dipublish dan aktif selama ' . $paket->batas_listing . ' hari.');
    }





    /**
     * Beli paket lalu ikatkan ke lowongan
     */
    public function beliPaket(Request $request)
    {
        $request->validate([
            'paket_id'    => 'required|exists:paket_lowongans,id',
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id',
        ]);

        $user = Auth::user();
        $perusahaan = $user->perusahaan;
        $paket = PaketLowongan::findOrFail($request->paket_id);

        $namaHarga = 'Pasang Lowongan ' . $paket->nama;
        $hargaKoin = HargaKoin::where('nama', $namaHarga)->first();

        if (!$hargaKoin) {
            return back()->with('error', 'Harga untuk paket ini belum diatur.');
        }

        if ($perusahaan->koin_perusahaan < $hargaKoin->harga) {
            return redirect()->route('paket.form')->with('koin_kurang', true);
        }

        $lowongan = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->where('id', $request->lowongan_id)
            ->firstOrFail();

        // Potong koin
        $perusahaan->decrement('koin_perusahaan', $hargaKoin->harga);

        // 🔥 LOGIKA INTI: Beli paket langsung mempublish lowongan secara otomatis
        if ($lowongan->published_at && $lowongan->expired_at && $lowongan->expired_at > now()) {
            // ➕ TAMBAH WAKTU (EXTEND)
            $lowongan->update([
                'paket_id'   => $paket->id,
                'expired_at' => $lowongan->expired_at->addDays($paket->batas_listing),
            ]);
            $pesanSukses = 'Paket berhasil dibeli. Masa aktif lowongan berhasil diperpanjang ' . $paket->batas_listing . ' hari.';
        } else {
            // 🚀 PUBLISH LANGSUNG
            $lowongan->update([
                'paket_id'     => $paket->id,
                'published_at' => now(),
                'expired_at'   => now()->addDays($paket->batas_listing),
            ]);
            $pesanSukses = 'Paket ' . $paket->nama . ' berhasil dibeli! Lowongan Anda kini telah AKTIF dan terbit selama ' . $paket->batas_listing . ' hari.';
        }

        CatatanKoin::create([
            'user_id'      => $user->id,
            'no_referensi' => 'KOIN-' . now()->format('YmdHis') . '-' . $user->id,
            'pesanan'      => 'Pembelian Paket ' . $paket->nama,
            'dari'         => $perusahaan->nama_perusahaan,
            'sumber_dana'  => 'Saldo Koin Perusahaan',
            'total'        => '-' . $hargaKoin->harga,
        ]);

        return redirect()->route('lowongan.saya.perusahaan')
            ->with('success', $pesanSukses);
    }


    //REKOMENDASI
    public function toggleRekomendasi($id)
    {
        $lowongan = LowonganPerusahaan::findOrFail($id);

        if ($lowongan->rekomendasi) {
            // Jika sedang direkomendasikan, hapus timestamp (jadikan NULL)
            $lowongan->rekomendasi = null;
            $pesan = 'Lowongan berhasil dihapus dari rekomendasi.';
        } else {
            // Jika tidak direkomendasikan → beri timestamp now()
            $lowongan->rekomendasi = now();
            $pesan = 'Lowongan berhasil dijadikan rekomendasi.';
        }

        $lowongan->save();

        return redirect()->back()->with('success', $pesan);
    }



    //SUPER ADMIN
    public function createFormSuper($id)
    {
        $pakets = PaketLowongan::all();
        $perusahaan = Perusahaan::findOrFail($id);
        $categories = Category::all();
        return view('super_admin.perusahaan.tambah-lowongan', [
            'perusahaan' => $perusahaan,
            'pakets' => $pakets,
            'categories' => $categories,
        ]);
    }


    public function storeSuper(Request $request, $id)
    {
        // Validasi input
        $valid = $request->validate([
            "nama"    => "required",
            "alamat"  => "required",
            "jenis"   => "required",
            "gaji_awal"  => "required",
            "gaji_akhir"  => "required",
            "deskripsi"   => "required",
            "syarat_pekerjaan"  => "required",
            "batas_lamaran" => "required",
            'kategori' => 'nullable',
            'benefit' => 'nullable',
            'tanggung_jawab' => 'nullable',
        ]);

        // Pastikan user yang login adalah super_admin
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menambahkan lowongan.');
        }

        // Ambil data perusahaan berdasarkan ID
        $perusahaan = Perusahaan::findOrFail($id);

        // Simpan lowongan baru
        $valid['perusahaan_id'] = $perusahaan->id;
        $valid['slug'] = Str::slug($request->nama . '-' . time());
        // $valid['tanggung_jawab'] = $perusahaan->nama_perusahaan;

        LowonganPerusahaan::create($valid);

        // =====================================================
        // Buat notifikasi untuk Super Admin
        // =====================================================
        Notifikasi::create([
            'user_id' => Auth::id(),
            'perusahaan_id' => $perusahaan->id,
            'judul'   => 'Lowongan Baru Ditambahkan',
            'pesan'   => 'Kamu berhasil menambahkan lowongan untuk ' . $perusahaan->nama_perusahaan,
            'is_read' => false,
            'expired_at' => now()->addDays(7),
        ]);

        // =====================================================
        // Buat notifikasi untuk perusahaan yang bersangkutan
        // =====================================================
        // Pastikan perusahaan memiliki user
        if ($perusahaan->user_id ?? false) {
            Notifikasi::create([
                'user_id' => $perusahaan->user_id,
                // 'perusahaan_id' => $perusahaan->id,
                'judul'   => 'Lowongan Baru dari ' . $perusahaan->nama_perusahaan,
                'pesan'   => 'Lowongan baru telah ditambahkan oleh Super Admin.',
                'is_read' => false,
                'expired_at' => now()->addDays(7),
            ]);
        }

        // Notif untuk semua Admin
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'perusahaan_id' => $perusahaan->id,
                'judul'   => 'Lowongan Baru Ditambahkan',
                'pesan'   => 'Super Admin menambahkan lowongan untuk ' . $perusahaan->nama_perusahaan,
                'is_read' => false,
                'expired_at' => now()->addDays(7),
            ]);
        }

        return redirect()
            ->route('superadmin.perusahaan.detail', $perusahaan->id)
            ->with('success', 'Lowongan berhasil ditambahkan! Notifikasi dikirim ke perusahaan.');
    }

    public function editSuper(LowonganPerusahaan $lowongan)
    {
        $categories = Category::all();
        return view('super_admin.perusahaan.edit-lowongan', [
            "lowongan" => $lowongan,
            "categories" => $categories,
        ]);
    }

    public function updateSuper(Request $request, LowonganPerusahaan $lowongan)
    {
        $valid = $request->validate([
            'nama' => 'nullable|string|max:255',
            'jenis' => 'nullable|string',
            'gaji_awal' => 'nullable|numeric',
            'gaji_akhir' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'kategori' => 'nullable|string',
            'batas_lamaran' => 'nullable|date',
            'deskripsi' => 'nullable|string',
            'syarat_pekerjaan' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
            'benefit' => 'nullable|string',
        ]);

        $valid['perusahaan_id'] = $lowongan->perusahaan->id;
        if ($request->filled('nama')) {
            $valid['slug'] = Str::slug($request->nama . '-' . time());
        }
        $lowongan->update($valid);
        return redirect()->route('superadmin.lowongan.detail', [
            'perusahaan' => $lowongan->perusahaan->slug ?? $lowongan->perusahaan_id,
            'lowongan'   => $lowongan->id
        ])->with('success', 'Lowongan berhasil diperbarui.');
    }


    public function destroySuper(LowonganPerusahaan $lowongan)
    {
        $perusahaanId = $lowongan->perusahaan_id;
        $lowongan->delete();
        return redirect()->route('superadmin.perusahaan.detail', $perusahaanId)->with('success', 'Lowongan berhasil dihapus.');
    }



    public function boost(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id'
        ]);

        $user = Auth::user();
        $perusahaan = $user->perusahaan;

        // ambil harga boost dari tabel harga_koins
        $hargaBoost = HargaKoin::where('nama', 'Boost Lowongan')->value('harga');

        if (!$hargaBoost) {
            return response()->json([
                'success' => false,
                'message' => 'Harga boost belum tersedia.'
            ], 422);
        }

        // cek koin perusahaan
        if ($perusahaan->koin_perusahaan < $hargaBoost) {
            return response()->json([
                'success' => false,
                'koin_kurang' => true,
                'message' => 'Koin perusahaan tidak mencukupi.'
            ]);
        }

        // ambil lowongan milik perusahaan & sudah publish
        $lowongan = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->whereNotNull('published_at')
            ->findOrFail($request->lowongan_id);

        // potong koin
        $perusahaan->decrement('koin_perusahaan', $hargaBoost);

        // simpan waktu boost terakhir
        $lowongan->update([
            'boosted_until' => now()
        ]);

        // catatan koin
        CatatanKoin::create([
            'user_id'      => $user->id,
            'no_referensi' => 'BOOST-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'pesanan'      => 'Boost Lowongan: ' . $lowongan->nama,
            'dari'         => 'Koin Perusahaan',
            'sumber_dana'  => 'boost-lowongan',
            'total'        => -$hargaBoost,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil di-boost.'
        ]);
    }
}
