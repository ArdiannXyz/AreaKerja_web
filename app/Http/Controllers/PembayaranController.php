<?php

namespace App\Http\Controllers;

use App\Models\DaftarBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bank'       => 'required|string|max:255',
            'nama'       => 'required|string|max:255',
            'nomor'      => 'required|string|max:255',
            'logo_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['bank', 'nama', 'nomor']);

        // Simpan nama_bank agar konsisten dengan kolom di tabel daftar_bank
        $data['nama_bank'] = $data['bank'];
        unset($data['bank']);

        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $request->file('logo_image')->store('logos', 'public');
        }

        DaftarBank::create($data);

        return redirect()->back()->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }
}
