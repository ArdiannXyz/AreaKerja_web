<?php

namespace App\Http\Controllers;

use App\Models\PelamarLowongan;
use App\Models\PembeliKandidat;
use App\Models\Perusahaan;
use App\Models\TalentHunter;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SuperAdminRecruitmentController extends Controller
{
    /**
     * Data Perusahaan yang Memiliki Panggilan Kerja
     */
    public function panggilan(Request $request)
    {
        $search = $request->input('search');

        $perusahaans = Perusahaan::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_perusahaan', 'like', '%' . $search . '%')
                    ->orWhere('kota', 'like', '%' . $search . '%')
                    ->orWhere('provinsi', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%')
                    ->orWhereHas('alamat_perusahaan', function ($apq) use ($search) {
                        $apq->where('desa', 'like', "%{$search}%")
                            ->orWhereHas('provinsi', function ($pr) use ($search) {
                                $pr->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('kota', function ($kt) use ($search) {
                                $kt->where('nama', 'like', "%{$search}%");
                            });
                    });
            })
            ->with([
                'pasanglowongan.pelamar' => function ($query) {
                    $query->where('pelamar_lowongans.status', 'diterima');
                },
                'alamat_perusahaan' => function ($query) {
                    $query->where('utama', 1)
                        ->with(['provinsi', 'kota', 'kecamatan']);
                }
            ])
            ->get();

        return view('super_admin.panggilan.data-panggilan', [
            'perusahaans' => $perusahaans,
            'search'      => $search,
        ]);
    }

    /**
     * Daftar Nama Pekerja yang Diterima
     */
    public function listPekerja(Request $request, $perusahaan_id)
    {
        $search = $request->input('search');

        $perusahaan = Perusahaan::with(['pasanglowongan'])->findOrFail($perusahaan_id);

        $pelamarDiterima = PelamarLowongan::with(['pelamar.user', 'lowongan_perusahaan'])
            ->where('status', 'diterima')
            ->whereHas('lowongan_perusahaan', function ($q) use ($perusahaan_id) {
                $q->where('perusahaan_id', $perusahaan_id);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('pelamar', function ($pel) use ($search) {
                        $pel->where('nama_pelamar', 'like', "%$search%")
                            ->orWhere('kota', 'like', "%$search%")
                            ->orWhere('provinsi', 'like', "%$search%")
                            ->orWhereHas('user', function ($u) use ($search) {
                                $u->where('email', 'like', "%$search%")
                                  ->orWhere('username', 'like', "%$search%");
                            });
                    })
                    ->orWhereHas('lowongan_perusahaan', function ($low) use ($search) {
                        $low->where('nama', 'like', "%$search%");
                    });
                });
            })
            ->get()
            ->map(function ($item) {
                return [
                    'nama'             => $item->pelamar->nama_pelamar,
                    'email'            => $item->pelamar->user->email ?? '-',
                    'lowongan'         => $item->lowongan_perusahaan->nama,
                    'tanggal_diterima' => $item->updated_at->format('d M Y'),
                    'jenis'            => 'pelamar_melamar'
                ];
            });

        $waAdmin = '6287874732189';
        $waUrl = "https://wa.me/{$waAdmin}";

        return view('super_admin.panggilan.list-nama-pekerja', [
            'perusahaan'      => $perusahaan,
            'pelamarDiterima' => $pelamarDiterima,
            'search'          => $search,
            'waUrl'           => $waUrl,
        ]);
    }

    /**
     * Daftar Permintaan Talent Hunter
     */
    public function talentHunterForm(Request $request)
    {
        $keyword = $request->input('search');

        $talentHunter = TalentHunter::with(['perusahaan.user'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('posisi', 'like', "%{$keyword}%")
                    ->orWhereHas('perusahaan', function ($q2) use ($keyword) {
                        $q2->where('nama_perusahaan', 'like', "%{$keyword}%")
                            ->orWhere('kota', 'like', "%{$keyword}%")
                            ->orWhere('provinsi', 'like', "%{$keyword}%")
                            ->orWhere('alamat', 'like', "%{$keyword}%")
                            ->orWhereHas('user', function ($q3) use ($keyword) {
                                $q3->where('username', 'like', "%{$keyword}%")
                                   ->orWhere('email', 'like', "%{$keyword}%");
                            });
                    });
            })
            ->get();

        return view('super_admin.talent-hunter.data-talent-hunter', [
            'talentHunter' => $talentHunter,
            'search'       => $keyword,
        ]);
    }

    public function detailDataTalentHunter($id)
    {
        $talentHunter = TalentHunter::with('perusahaan')->findOrFail($id);
        return view('super_admin.talent-hunter.detail-data-talent-hunter', [
            'talentHunter' => $talentHunter,
        ]);
    }

    /**
     * Daftar Recruitment Kandidat Perusahaan
     */
    public function recruitment(Request $request, $id)
    {
        $search = $request->search;
        $perusahaan = Perusahaan::findOrFail($id);

        $recruitments = PembeliKandidat::where('status', 'diterima')
            ->whereHas('lowonganPerusahaan', function ($q) use ($id) {
                $q->where('perusahaan_id', $id);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('pelamar', function ($pel) use ($search) {
                        $pel->where('nama_pelamar', 'like', '%' . $search . '%')
                            ->orWhere('kota', 'like', '%' . $search . '%')
                            ->orWhere('provinsi', 'like', '%' . $search . '%')
                            ->orWhere('alamat', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($u) use ($search) {
                                $u->where('username', 'like', '%' . $search . '%')
                                  ->orWhere('email', 'like', '%' . $search . '%');
                            });
                    })
                        ->orWhereHas('lowonganPerusahaan', function ($low) use ($search) {
                            $low->where('nama', 'like', '%' . $search . '%');
                        });
                });
            })
            ->with(['pelamar', 'lowonganPerusahaan'])
            ->get();

        return view('super_admin.recruitment.data-recruitment', [
            'perusahaan'   => $perusahaan,
            'recruitments' => $recruitments,
            'search'       => $search,
        ]);
    }

    public function recruitmentPerusahaan(Request $request)
    {
        $search = $request->input('search');
        $perusahaan = Perusahaan::with('user')
            ->when($search, function ($query, $search) {
                $query->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('provinsi', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->get();

        return view('super_admin.recruitment.perusahaan', [
            'perusahaan' => $perusahaan,
            'search'     => $search,
        ]);
    }

    public function detailRecruitment($id)
    {
        $recruitment = PembeliKandidat::with([
            'pelamar.user',
            'pelamar.sosmed',
            'pelamar.pengalaman_organisasi',
            'pelamar.pengalaman_kerja',
            'pelamar.riwayat_pendidikan',
            'pelamar.alamat_pelamar',
            'pelamar.skill',
            'lowonganPerusahaan.perusahaan.alamatUtama',
            'lowonganPerusahaan.perusahaan'
        ])->findOrFail($id);

        return view('super_admin.recruitment.detail-recruitment', [
            'recruitment' => $recruitment,
        ]);
    }

    public function destroyRecruitment($id)
    {
        $pembelian = PembeliKandidat::with([
            'pelamar.user',
            'lowonganPerusahaan.perusahaan'
        ])->findOrFail($id);

        $user = $pembelian->pelamar->user;
        $perusahaan = $pembelian->lowonganPerusahaan->perusahaan ?? null;
        $perusahaanUser = $perusahaan->user ?? null;

        $pembelian->delete();

        if ($user && $perusahaan) {
            NotificationService::send(
                $user->id,
                'Status Recruitment Dibatalkan',
                'Status Recruitment Anda telah dibatalkan oleh Admin.',
                ['perusahaan_id' => $perusahaan->id]
            );
        }

        if ($perusahaanUser) {
            NotificationService::send(
                $perusahaanUser->id,
                'Status Recruitment Dibatalkan',
                'Kandidat ' . $pembelian->pelamar->nama_pelamar . ' telah dihapus dari daftar recruitment oleh Admin.'
            );
        }

        return redirect()->route('superadmin.recruitment', $perusahaan->id)
            ->with('success', 'Recruitment berhasil dihapus & pelamar kembali menjadi kandidat biasa.');
    }
}

