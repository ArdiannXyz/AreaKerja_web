<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\PengalamanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengalamanKerjaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi_pekerjaan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan_pekerjaan' => 'nullable|string|max:255',
            'tahun_awal' => 'required|digits:4|integer',
            'tahun_akhir' => 'nullable|digits:4|integer|gte:tahun_awal',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['pelamar_id'] = Auth::user()->pelamar->id;

        PengalamanKerja::create($validated);
        return redirect()->route('profile.index')->with('success', 'Pengalaman Kerja berhasil disimpan');
    }

    public function update(Request $request, PengalamanKerja $kerja)
    {
        $validated = $request->validate([
            'posisi_pekerjaan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan_pekerjaan' => 'nullable|string|max:255',
            'tahun_awal' => 'required|digits:4|integer',
            'tahun_akhir' => 'nullable|digits:4|integer|gte:tahun_awal',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['pelamar_id'] = Auth::user()->pelamar->id;


        $kerja->update($validated);
        return redirect()->route('profile.index')->with('success', 'Pengalaman Kerja berhasil diupdate');
    }

    public function edit(PengalamanKerja $kerja)
    {
        return view('non-user.profile.kerja.edit', ['DK' => $kerja]);
    }

    public function destroy(PengalamanKerja $kerja)
    {
        $kerja->delete();
        return redirect()->back()->with('success', 'Pengalaman Kerja berhasil dihapus');
    }

    public function storeSuper(Request $request)
    {
        $validated = $request->validate([
            'pelamar_id' => 'nullable|exists:pelamars,id',
            'posisi_pekerjaan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan_pekerjaan' => 'nullable|string|max:255',
            'tahun_awal' => 'required|digits:4|integer',
            'tahun_akhir' => 'nullable|digits:4|integer|gte:tahun_awal',
            'deskripsi' => 'nullable|string',
        ]);

        $pelamar_id = $request->pelamar_id ?? session('pelamar_terakhir_id');


        if (!$pelamar_id) {
            return back()->with('error', 'Pelamar belum dibuat. Harap buat pelamar terlebih dahulu sebelum menambahkan pendidikan.');
        }

        $validated['pelamar_id'] = $pelamar_id;

        PengalamanKerja::create($validated);

        // Jika sedang di halaman edit pelamar → balik ke halaman sebelumnya
        if ($request->has('is_edit')) {
            return redirect()->back()->with('success', 'Pengalaman berhasil ditambahkan');
        }


        $pelamar = Pelamar::find($pelamar_id);


        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        return redirect()->route('superadmin.pelamar.create', ['kategori' => $kategori])
            ->with('success', 'Pengalaman berhasil disimpan');
    }

    public function updateSuper(Request $request, ?PengalamanKerja $kerja = null)
    {
        $validated = $request->validate([
            'pelamar_id' => 'required|exists:pelamars,id',
            'posisi_pekerjaan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan_pekerjaan' => 'nullable|string|max:255',
            'tahun_awal' => 'required|integer',
            'tahun_akhir' => 'nullable|integer|gte:tahun_awal',
            'deskripsi' => 'nullable|string',
        ]);

        $pelamar_id = $validated['pelamar_id'];


        if ($kerja && $kerja->exists) {
            $kerja->update($validated);
        } else {
            $kerja = PengalamanKerja::create($validated);
        }

        $pelamar = Pelamar::find($pelamar_id);

        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        // $kerja->update($validated);
        return redirect()->route('superadmin.pelamar.edit', [
            'kategori' => $kategori,
            'id' => $pelamar_id
        ])->with('success', 'Data Pengalaman Kerja berhasil disimpan.');
    }

    public function editSuper(PengalamanKerja $kerja)
    {
        return view('super_admin.pelamar.modal.edit.edit_pengalaman', ['DK' => $kerja]);
    }

    public function destroySuper(PengalamanKerja $kerja)
    {
        return $this->destroy($kerja);
    }
}
