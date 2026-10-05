<?php

namespace App\Http\Controllers;

use App\Models\DaftarBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $banks = DaftarBank::orderBy('id', 'asc')->get();
        return view('finance.bank.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank'  => 'required|string|max:100',
            'owner'      => 'required|string|max:255',
            'no_rek'     => 'required|string|max:100',
            'logo_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'nama_bank.required' => 'Nama bank/metode wajib diisi.',
            'owner.required'     => 'Atas nama rekening wajib diisi.',
            'no_rek.required'    => 'Nomor rekening wajib diisi.',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_image')) {
            $logoPath = $request->file('logo_image')->store('logos', 'public');
        }

        DaftarBank::create([
            'nama_bank'  => $request->nama_bank,
            'owner'      => $request->owner,
            'no_rek'     => $request->no_rek,
            'logo_image' => $logoPath,
        ]);

        return redirect()->route('finance.bank.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $bank = DaftarBank::findOrFail($id);

        $request->validate([
            'nama_bank'  => 'required|string|max:100',
            'owner'      => 'required|string|max:255',
            'no_rek'     => 'required|string|max:100',
            'logo_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'nama_bank.required' => 'Nama bank/metode wajib diisi.',
            'owner.required'     => 'Atas nama rekening wajib diisi.',
            'no_rek.required'    => 'Nomor rekening wajib diisi.',
        ]);

        $data = [
            'nama_bank' => $request->nama_bank,
            'owner'     => $request->owner,
            'no_rek'    => $request->no_rek,
        ];

        if ($request->hasFile('logo_image')) {
            if ($bank->logo_image && Storage::disk('public')->exists($bank->logo_image)) {
                Storage::disk('public')->delete($bank->logo_image);
            }
            $data['logo_image'] = $request->file('logo_image')->store('logos', 'public');
        }

        $bank->update($data);

        return redirect()->route('finance.bank.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bank = DaftarBank::findOrFail($id);

        if ($bank->catatanCash()->count() > 0) {
            return redirect()->route('finance.bank.index')->with('error', 'Metode pembayaran tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        if ($bank->logo_image && Storage::disk('public')->exists($bank->logo_image)) {
            Storage::disk('public')->delete($bank->logo_image);
        }

        $bank->delete();

        return redirect()->route('finance.bank.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
