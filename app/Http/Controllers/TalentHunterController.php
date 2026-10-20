<?php

namespace App\Http\Controllers;

use App\Models\CatatanCash;
use App\Models\DaftarBank;
use App\Models\Perusahaan;
use App\Models\TalentHunter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TalentHunterController extends Controller
{
    // ambil info harga / ketentuan minimal gaji
    public function getHarga()
    {
        return response()->json([
            'min_gaji'        => 3500000,
            'format_min_gaji' => 'Rp 3.500.000',
        ]);
    }

    // proses validasi awal
    public function beli()
    {
        return response()->json([
            'success' => true,
            'message' => 'Silakan lengkapi formulir Talent Hunter.'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat'           => 'required|string|max:255',
            'posisi'           => 'required|string|max:255',
            'pengalaman_kerja' => 'required|string',
            'gender'           => 'required|string',
            'gaji_ditawarkan'  => 'required|numeric|min:3500000',
            'deskripsi'        => 'nullable|string',
            'daftar_bank_id'   => 'nullable|exists:daftar_bank,id',
        ], [
            'gaji_ditawarkan.required' => 'Gaji yang ditawarkan wajib diisi.',
            'gaji_ditawarkan.numeric'  => 'Gaji yang ditawarkan harus berupa angka.',
            'gaji_ditawarkan.min'      => 'Gaji yang ditawarkan minimal Rp 3.500.000.',
        ]);

        $user       = Auth::user();
        $perusahaan = $user->perusahaan;

        // Tentukan bank tujuan pembayaran
        $bankId = $request->daftar_bank_id;
        if (!$bankId) {
            $bank = DaftarBank::first();
            if (!$bank) {
                $bank = DaftarBank::create([
                    'nama_bank' => 'Xendit Payment',
                    'no_rek'    => 'XENDIT',
                    'owner'     => 'PT Area Kerja',
                ]);
            }
            $bankId = $bank->id;
        } else {
            $bank = DaftarBank::find($bankId);
        }

        // Simpan data Talent Hunter
        $talentHunter = $perusahaan->talentHunters()->create([
            'alamat'           => $validated['alamat'],
            'posisi'           => $validated['posisi'],
            'pengalaman_kerja' => $validated['pengalaman_kerja'],
            'gender'           => $validated['gender'],
            'gaji_ditawarkan'  => $validated['gaji_ditawarkan'],
            'gaji_awal'        => $validated['gaji_ditawarkan'],
            'gaji_akhir'       => $validated['gaji_ditawarkan'],
            'deskripsi'        => $validated['deskripsi'] ?? null,
        ]);

        $noReferensi = 'TH-' . strtoupper(uniqid());
        $sumberDana  = ($bank && strtolower($bank->nama_bank) === 'qris') ? 'Qris' : 'Transfer Bank';

        // Buat data transaksi tagihan (CatatanCash) seperti flow pendaftaran kandidat pelamar
        $transaksi = CatatanCash::create([
            'user_id'        => $user->id,
            'daftar_bank_id' => $bankId,
            'no_referensi'   => $noReferensi,
            'pesanan'        => 'Talent Hunter: ' . $talentHunter->posisi,
            'dari'           => $perusahaan->nama_perusahaan ?? $user->username,
            'sumberDana'     => $sumberDana,
            'total'          => $talentHunter->gaji_ditawarkan,
            'status'         => 'pending',
            'expired_at'     => now()->addHours(24),
        ]);

        // Buat invoice Xendit
        app(\App\Services\XenditService::class)->createInvoice(
            $transaksi,
            route('catatan_cash.show', $transaksi->id),
            route('catatan_cash.show', $transaksi->id)
        );

        $transaksi->refresh();
        $nominalFormat = 'Rp ' . number_format($talentHunter->gaji_ditawarkan, 0, ',', '.');
        $redirectUrl = route('catatan_cash.show', $transaksi->id);

        return response()->json([
            'success'      => true,
            'message'      => 'Pengajuan Talent Hunter berhasil dibuat! Mengalihkan ke halaman pembayaran...',
            'redirect_url' => $redirectUrl,
            'checkout_url' => $transaksi->checkout_url,
            'no_referensi' => $noReferensi,
            'nominal'      => $nominalFormat,
            'transaksi_id' => $transaksi->id,
        ]);
    }


    public function index()
    {
        $user = auth()->user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();
        $daftarBank = DaftarBank::all();

        return view('perusahaan.talent-hunter.talent-hunter', [
            'perusahaan' => $perusahaan,
            'daftarBank' => $daftarBank,
        ]);
    }

    public function editTalentHunter($id)
    {
        $talentHunter = TalentHunter::with('perusahaan.user')->findOrFail($id);
        return view('super_admin.talent-hunter.edit-data-talent-hunter', [
            'talentHunter' => $talentHunter,
            'perusahaan'   => $talentHunter->perusahaan,
            'user'         => $talentHunter->perusahaan->user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'alamat'           => 'required|string',
            'posisi'           => 'required|string',
            'pengalaman_kerja' => 'nullable|string',
            'gender'           => 'nullable|string',
            'gaji_ditawarkan'  => 'nullable|numeric|min:3500000',
            'deskripsi'        => 'nullable|string',
        ], [
            'gaji_ditawarkan.min' => 'Gaji yang ditawarkan minimal Rp 3.500.000.',
        ]);

        $talentHunter = TalentHunter::findOrFail($id);
        $data = $request->only([
            'alamat',
            'posisi',
            'pengalaman_kerja',
            'gender',
            'gaji_ditawarkan',
            'deskripsi',
        ]);

        if (isset($data['gaji_ditawarkan'])) {
            $data['gaji_awal'] = $data['gaji_ditawarkan'];
            $data['gaji_akhir'] = $data['gaji_ditawarkan'];
        }

        $talentHunter->update($data);

        return redirect()->route('superadmin.talent-hunter.detail', $talentHunter->id)
            ->with('success', 'Data Talent Hunter berhasil diperbarui!');
    }
}
