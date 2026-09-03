<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\PengalamanOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengalamanOrgController extends Controller
{
    public function create($pelamar_id = null)
    {
        return redirect()->route('profile.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'tahun_awal'      => 'required|digits:4|integer',
            'tahun_akhir'     => 'nullable|digits:4|integer|gte:tahun_awal',
            'deskripsi'       => 'nullable|string',
        ]);

        $pelamar = Auth::user()->pelamar;

        PengalamanOrganisasi::create([
            'pelamar_id'      => $pelamar->id,
            'nama_organisasi' => $validated['nama_organisasi'],
            'jabatan'         => $validated['jabatan'],
            'tahun_awal'      => $validated['tahun_awal'],
            'tahun_akhir'     => $validated['tahun_akhir'] ?? null,
            'deskripsi'       => $validated['deskripsi'] ?? null,
        ]);

        return redirect()->route('profile.index')->with('success', 'Pengalaman Organisasi berhasil disimpan');
    }

    public function edit($id)
    {
        $DT = PengalamanOrganisasi::findOrFail($id);
        return view('non-user.profile.organisasi.edit', compact('DT'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'tahun_awal'      => 'required|digits:4|integer',
            'tahun_akhir'     => 'nullable|digits:4|integer|gte:tahun_awal',
            'deskripsi'       => 'nullable|string',
        ]);

        $org = PengalamanOrganisasi::findOrFail($id);
        $org->update([
            'nama_organisasi' => $validated['nama_organisasi'],
            'jabatan'         => $validated['jabatan'],
            'tahun_awal'      => $validated['tahun_awal'],
            'tahun_akhir'     => $validated['tahun_akhir'] ?? null,
            'deskripsi'       => $validated['deskripsi'] ?? null,
        ]);

        return redirect()->route('profile.index')->with('success', 'Pengalaman Organisasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $org = PengalamanOrganisasi::findOrFail($id);
        $org->delete();

        return redirect()->back()->with('success', 'Pengalaman Organisasi berhasil dihapus');
    }

    public function storeSuper(Request $request)
    {
        $validated = $request->validate([
            'pelamar_id'      => 'nullable|exists:pelamars,id',
            'nama_organisasi' => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'tahun_awal'      => 'required|digits:4|integer',
            'tahun_akhir'     => 'nullable|digits:4|integer|gte:tahun_awal',
            'deskripsi'       => 'nullable|string',
        ]);

        $pelamar_id = $request->pelamar_id ?? session('pelamar_terakhir_id');

        if (!$pelamar_id) {
            return back()->with('error', 'Pelamar belum dibuat. Harap buat pelamar terlebih dahulu.');
        }

        PengalamanOrganisasi::create([
            'pelamar_id'      => $pelamar_id,
            'nama_organisasi' => $validated['nama_organisasi'],
            'jabatan'         => $validated['jabatan'],
            'tahun_awal'      => $validated['tahun_awal'],
            'tahun_akhir'     => $validated['tahun_akhir'] ?? null,
            'deskripsi'       => $validated['deskripsi'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Pengalaman Organisasi berhasil disimpan');
    }

    public function updateSuper(Request $request, $id = null)
    {
        if ($id) {
            $validated = $request->validate([
                'nama_organisasi' => 'required|string|max:255',
                'jabatan'         => 'required|string|max:255',
                'tahun_awal'      => 'required|digits:4|integer',
                'tahun_akhir'     => 'nullable|digits:4|integer|gte:tahun_awal',
                'deskripsi'       => 'nullable|string',
            ]);

            $org = PengalamanOrganisasi::findOrFail($id);
            $org->update($validated);
        }

        return redirect()->back()->with('success', 'Data organisasi berhasil disimpan.');
    }

    public function editSuper($id = null)
    {
        $DT = PengalamanOrganisasi::find($id) ?? (object)[
            'id'              => $id,
            'nama_organisasi' => 'Organisasi',
            'jabatan'         => 'Anggota',
            'tahun_awal'      => '2020',
            'tahun_akhir'     => '2022',
            'deskripsi'       => '',
        ];
        return view('super_admin.pelamar.modal.edit.edit_organisasi', ['DT' => $DT]);
    }

    public function destroySuper($id = null)
    {
        if ($id) {
            PengalamanOrganisasi::destroy($id);
        }
        return redirect()->back()->with('success', 'Organisasi berhasil dihapus');
    }
}
