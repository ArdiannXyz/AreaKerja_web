<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
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
        return redirect()->route('profile.index')->with('success', 'Organisasi berhasil disimpan');
    }

    public function update(Request $request, $id = null)
    {
        return redirect()->route('profile.index')->with('success', 'Organisasi berhasil diperbarui');
    }

    public function edit($id = null)
    {
        $DT = (object)[
            'id'              => $id,
            'nama_organisasi' => 'Organisasi',
            'jabatan'         => 'Anggota',
            'tahun_awal'      => '2020',
            'tahun_akhir'     => '2022',
            'deskripsi'       => '',
        ];
        return view('non-user.profile.organisasi.edit', ['DT' => $DT]);
    }

    public function destroy($id = null)
    {
        return redirect()->back()->with('success', 'Organisasi berhasil dihapus');
    }

    public function storeSuper(Request $request)
    {
        return redirect()->back()->with('success', 'Organisasi berhasil disimpan');
    }

    public function updateSuper(Request $request, $id = null)
    {
        return redirect()->back()->with('success', 'Data organisasi berhasil disimpan.');
    }

    public function editSuper($id = null)
    {
        $DT = (object)[
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
        return redirect()->back()->with('success', 'Organisasi berhasil dihapus');
    }
}
