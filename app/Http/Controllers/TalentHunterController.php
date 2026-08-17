<?php

namespace App\Http\Controllers;

use App\Models\DaftarBank;
use App\Models\Hargakoin;
use App\Models\HargaPembayaran;
use App\Models\Perusahaan;
use App\Models\TalentHunter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TalentHunterController extends Controller
{
    //ambil harga koin
    public function getHarga()
    {

        $harga = Hargakoin::where('nama', 'Open Talent Hunter')->first();
        $perusahaan = Auth::user()->perusahaan;

        return response()->json([
            'harga' => $harga->harga ?? 0,
            'koin_perusahaan' => $perusahaan->koin_perusahaan ?? 0,
        ]);
    }


    //proses pembelian
    public function beli()
    {
        $user = Auth::user();
        $perusahaan = $user->perusahaan;
        $harga = Hargakoin::where('nama', 'Open Talent Hunter')->firstOrFail();

        //cek apakah perusahaan memiliki cukup koin
        if ($perusahaan->koin_perusahaan < $harga->harga) {
            return response()->json(['success' => false, 'message' => 'Koin tidak cukup.']);
        }

        return response()->json(['success' => true, 'message' => 'Silakan isi form Talent Hunter.']);
    }

        public function store(Request $request)
    {
        $request->validate([
            'alamat'           => 'required',
            'posisi'           => 'required',
            'pengalaman_kerja' => 'required',
            'gender'           => 'required',
            'gaji_awal'        => 'required',
            'gaji_akhir'       => 'required',
            'deskripsi'        => 'nullable',
        ]);

        $user       = Auth::user();
        $perusahaan = $user->perusahaan;
        $harga      = Hargakoin::where('nama', 'Open Talent Hunter')->firstOrFail();

        // Pastikan koin cukup sebelum masuk transaction
        if ($perusahaan->koin_perusahaan < $harga->harga) {
            return response()->json(['success' => false, 'message' => 'Koin tidak cukup.']);
        }

        $noReferensi = 'TH-' . strtoupper(uniqid());

        // ✅ SEMUA OPERASI DALAM SATU TRANSACTION
        $talentHunter = DB::transaction(function () use ($user, $perusahaan, $harga, $request, $noReferensi) {

            // 1. Potong koin dulu (decrement lebih aman dari race condition)
            $perusahaan->decrement('koin_perusahaan', $harga->harga);

            // 2. Simpan data talent hunter
            $talentHunter = $perusahaan->talentHunters()->create(
                $request->only(['alamat', 'posisi', 'pengalaman_kerja', 'gender', 'gaji_awal', 'gaji_akhir', 'deskripsi'])
            );

            // 3. Catat transaksi koin
            $user->catatanKoins()->create([
                'no_referensi' => $noReferensi,
                'pesanan'      => 'Pembelian Talent Hunter',
                'dari'         => $perusahaan->nama_perusahaan,
                'sumber_dana'  => 'Koin Perusahaan',
                'total'        => '-' . $harga->harga,
            ]);

            return $talentHunter;
        });

        // Redirect ke WhatsApp setelah transaction selesai
        $nomorAdmin = env('ADMIN_WHATSAPP', '6287874732189'); // ← sudah pakai .env (Bug #5)
        $pesan = "Halo Admin, saya sudah melakukan pembelian Talent Hunter.\n\n"
            . "Posisi: {$talentHunter->posisi}\n"
            . "No Referensi: {$noReferensi}\n"
            . "Mohon tindak lanjutnya.";

        $waUrl = 'https://wa.me/' . $nomorAdmin . '?text=' . urlencode($pesan);

        return response()->json([
            'success'      => true,
            'message'      => 'Talent Hunter berhasil disimpan.',
            'redirect_url' => $waUrl,
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();
        return view('perusahaan.talent-hunter.talent-hunter', [
            'perusahaan' => $perusahaan,
            'hargaPembayarans' => HargaPembayaran::where('jumlah_koin', '>', 0)->get(),
            'daftarBank' => DaftarBank::all(),
        ]);
    }


    public function editTalentHunter($id)
    {
        $talentHunter = TalentHunter::with('perusahaan.user')->findOrFail($id);
        return view('super_admin.talent-hunter.edit-data-talent-hunter', [
            'talentHunter' => $talentHunter,
            'perusahaan' => $talentHunter->perusahaan,
            'user' => $talentHunter->perusahaan->user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'alamat' => 'required|string',
            'posisi' => 'required|string',
            'pengalaman_kerja' => 'nullable|string',
            'gender' => 'nullable|string',
            'gaji_awal' => 'nullable|numeric',
            'gaji_akhir' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        $talentHunter = TalentHunter::findOrFail($id);
        $talentHunter->update($request->only([
            'alamat',
            'posisi',
            'pengalaman_kerja',
            'gender',
            'gaji_awal',
            'gaji_akhir'
        ]));

        return redirect()->route('superadmin.talent-hunter.detail', $talentHunter->id)->with('success', 'Data Talent Hunter berhasil diperbarui!');
    }
}
