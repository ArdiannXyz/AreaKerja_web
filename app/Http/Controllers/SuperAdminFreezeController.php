<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminFreezeController extends Controller
{
    /**
     * Tampilan daftar akun yang dibekukan / aktif
     */
    public function freezeForm(Request $request)
    {
        $search = $request->input('search');

        $data = User::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    // Pelamar (wilayah & kategori)
                    ->orWhereHas('pelamar', function ($pq) use ($search) {
                        $pq->where('provinsi', 'like', "%{$search}%")
                            ->orWhere('kota', 'like', "%{$search}%")
                            ->orWhere('alamat', 'like', "%{$search}%")
                            ->orWhere('kategori', 'like', "%{$search}%")
                            ->orWhere('nama_pelamar', 'like', "%{$search}%");
                    })
                    // Perusahaan (wilayah)
                    ->orWhereHas('perusahaan', function ($cq) use ($search) {
                        $cq->where('provinsi', 'like', "%{$search}%")
                            ->orWhere('kota', 'like', "%{$search}%")
                            ->orWhere('alamat', 'like', "%{$search}%")
                            ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                    })
                    // Admin (wilayah)
                    ->orWhereHas('admin', function ($aq) use ($search) {
                        $aq->where('detail_alamat', 'like', "%{$search}%")
                            ->orWhere('desa', 'like', "%{$search}%")
                            ->orWhereHas('provinsi', function ($prq) use ($search) {
                                $prq->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('kota', function ($ktq) use ($search) {
                                $ktq->where('nama', 'like', "%{$search}%");
                            });
                    });

                // Filter status aktif vs beku
                if (stripos($search, 'aktif') !== false) {
                    $q->orWhere('status', 0);
                }
                if (stripos($search, 'ban') !== false || stripos($search, 'freeze') !== false) {
                    $q->orWhere('status', 1);
                }
            });
        })->with(['pelamar', 'perusahaan'])->get();

        return view('super_admin.freeze.freeze', [
            'data'   => $data,
            'search' => $search,
        ]);
    }

    /**
     * Bekukan akun (Freeze / Ban)
     */
    public function ban(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => 'required|boolean',
        ]);

        $user->update($data);
        return redirect()->route('superadmin.freeze')->with('success', 'Akun berhasil di-freeze');
    }

    /**
     * Buka pembekuan akun (Unfreeze / Unban)
     */
    public function unban(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => 'required|boolean',
        ]);

        $user->update($data);
        return redirect()->route('superadmin.freeze')->with('success', 'Akun berhasil di-unfreeze');
    }

    /**
     * Hapus akun user
     */
    public function delete_akun(User $user)
    {
        $user->delete();
        return redirect()->route('superadmin.freeze')->with('success', 'Akun berhasil dihapus');
    }

    /**
     * Detail akun yang dibekukan
     */
    public function detail_freeze(User $user)
    {
        return view('super_admin.freeze.detail-freeze', [
            'data' => $user,
        ]);
    }
}

