<?php

namespace App\Http\Controllers;

use App\Models\PaketLowongan;
use Illuminate\Http\Request;

class ManajemenLowonganController extends Controller
{
    public function gold()
    {
        $paket = PaketLowongan::where('nama', 'Gold')->firstOrFail();
        return view('super_admin.manajemenlowongan.settinglowongangold', compact('paket'));
    }

    public function silver()
    {
        $paket = PaketLowongan::where('nama', 'Silver')->firstOrFail();
        return view('super_admin.manajemenlowongan.settinglowongansilver', compact('paket'));
    }

    public function bronze()
    {
        $paket = PaketLowongan::where('nama', 'Bronze')->firstOrFail();
        return view('super_admin.manajemenlowongan.settinglowonganbronze', compact('paket'));
    }

    public function updateGold(Request $request)
    {
        $paket = PaketLowongan::where('nama', 'Gold')->firstOrFail();

        $paket->update([
            'batas_listing' => $request->batas_listing,
            'benefit' => $request->benefit,
        ]);

        return back()->with('success', 'Paket Gold berhasil diperbarui.');
    }

    public function updateSilver(Request $request)
    {
        $paket = PaketLowongan::where('nama', 'Silver')->firstOrFail();

        $paket->update([
            'batas_listing' => $request->batas_listing,
            'benefit' => $request->benefit,
        ]);

        return back()->with('success', 'Paket Silver berhasil diperbarui.');
    }

    public function updateBronze(Request $request)
    {
        $paket = PaketLowongan::where('nama', 'Bronze')->firstOrFail();

        $paket->update([
            'batas_listing' => $request->batas_listing,
            'benefit' => $request->benefit,
        ]);

        return back()->with('success', 'Paket Bronze berhasil diperbarui.');
    }
}
