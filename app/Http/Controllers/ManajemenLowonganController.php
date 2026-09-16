<?php

namespace App\Http\Controllers;

use App\Models\PaketLowongan;
use Illuminate\Http\Request;

class ManajemenLowonganController extends Controller
{
    private function getPaketData($activeTab = 'gold')
    {
        $pakets = PaketLowongan::all()->keyBy(function ($item) {
            return strtolower($item->nama);
        });

        $gold = $pakets->get('gold') ?? PaketLowongan::where('nama', 'Gold')->first();
        $silver = $pakets->get('silver') ?? PaketLowongan::where('nama', 'Silver')->first();
        $bronze = $pakets->get('bronze') ?? PaketLowongan::where('nama', 'Bronze')->first();
        $paket = $pakets->get($activeTab) ?? $gold;

        return compact('gold', 'silver', 'bronze', 'paket', 'activeTab');
    }

    public function gold()
    {
        $data = $this->getPaketData('gold');
        return view('super_admin.manajemenlowongan.settinglowongangold', $data);
    }

    public function silver()
    {
        $data = $this->getPaketData('silver');
        return view('super_admin.manajemenlowongan.settinglowongangold', $data);
    }

    public function bronze()
    {
        $data = $this->getPaketData('bronze');
        return view('super_admin.manajemenlowongan.settinglowongangold', $data);
    }

    public function updateGold(Request $request)
    {
        $paket = PaketLowongan::where('nama', 'Gold')->firstOrFail();

        $paket->update([
            'batas_listing' => $request->batas_listing,
            'benefit' => $request->benefit,
        ]);

        return redirect()->route('superadmin.manajemen.lowongan.gold')->with('success', 'Paket Gold berhasil diperbarui.');
    }

    public function updateSilver(Request $request)
    {
        $paket = PaketLowongan::where('nama', 'Silver')->firstOrFail();

        $paket->update([
            'batas_listing' => $request->batas_listing,
            'benefit' => $request->benefit,
        ]);

        return redirect()->route('superadmin.manajemen.lowongan.silver')->with('success', 'Paket Silver berhasil diperbarui.');
    }

    public function updateBronze(Request $request)
    {
        $paket = PaketLowongan::where('nama', 'Bronze')->firstOrFail();

        $paket->update([
            'batas_listing' => $request->batas_listing,
            'benefit' => $request->benefit,
        ]);

        return redirect()->route('superadmin.manajemen.lowongan.bronze')->with('success', 'Paket Bronze berhasil diperbarui.');
    }
}
