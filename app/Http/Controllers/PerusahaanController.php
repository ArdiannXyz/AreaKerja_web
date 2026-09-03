<?php

namespace App\Http\Controllers;

use App\Models\AlamatPerusahaan;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\DaftarBank;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
    public function profile_perusahaan()
    {
        return view('perusahaan.profile.profile-perusahaan');
    }

    public function edit_profile()
    {
        return view('perusahaan.profile.edit');
    }

    public function update_profile_perusahaan(Request $request, Perusahaan $perusahaan)
    {
        try {
            $validated = $request->validate([
                'nama_perusahaan'     => "nullable|string",
                'jenis_perusahaan'    => "nullable|string",
                'website_perusahaan'  => "nullable|string",
                'telepon_perusahaan'  => [
                    "nullable",
                    "regex:/^(?:\+628|08)[0-9]+$/"
                ],
                'whatsapp'            => [
                    "nullable",
                    "regex:/^(?:628|08)[0-9]+$/"
                ],
                'legalitas'           => "nullable|string",
                'deskripsi'           => "nullable|string",
                'visi'                => "nullable|string",
                'misi'                => "nullable|string",
                'alamat'              => "nullable|string",
                'kota'                => "nullable|string",
                'provinsi'            => "nullable|string",
                'img_profile'         => "nullable|image|mimes:jpg,jpeg,png|max:2048",
            ], [
                'telepon_perusahaan.regex' => "Nomor telepon harus diawali 08, atau 628.",
                'whatsapp.regex'           => "Nomor WhatsApp harus diawali 08, atau 628.",
            ]);

            if (!empty($request->telepon_perusahaan)) {
                $telepon = preg_replace('/[^0-9]/', '', $request->telepon_perusahaan);
                $telepon = preg_replace('/^62/', '0', $telepon);
                $validated['telepon_perusahaan'] = $telepon;
            }

            if (!empty($request->whatsapp)) {
                $wa = preg_replace('/[^0-9]/', '', $request->whatsapp);
                $wa = preg_replace('/^62/', '0', $wa);
                $validated['whatsapp'] = $wa;
            }

            if ($request->hasFile('img_profile')) {
                if ($perusahaan->img_profile && Storage::exists('public/' . $perusahaan->img_profile)) {
                    Storage::delete('public/' . $perusahaan->img_profile);
                }
                $validated['img_profile'] = $request->file('img_profile')->store('images', 'public');
            }

            $validated['user_id'] = Auth::id();
            $perusahaan->update($validated);

            Notifikasi::create([
                'user_id'       => Auth::id(),
                'judul'         => 'Profil Berhasil Diperbarui',
                'pesan'         => 'Profil perusahaan Anda telah berhasil disimpan.',
                'is_read'       => 0,
                'expired_at'    => now()->addDays(7),
            ]);

            return redirect()->route('profile.perusahaan')->with('success', 'Profile berhasil diperbarui');
        } catch (\Exception $e) {
            Notifikasi::create([
                'user_id'       => Auth::id(),
                'judul'         => 'Gagal Memperbarui Profil',
                'pesan'         => 'Terjadi kesalahan saat menyimpan data profil perusahaan: ' . $e->getMessage(),
                'is_read'       => 0,
                'expired_at'    => now()->addDays(7),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Profil gagal diperbarui: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy_profile(Perusahaan $perusahaan)
    {
        if ($perusahaan->img_profile && Storage::exists('public/' . $perusahaan->img_profile)) {
            Storage::delete('public/' . $perusahaan->img_profile);
        }

        $perusahaan->img_profile = null;
        $perusahaan->save();
        return redirect()->route('profile.perusahaan')->with('success', 'Profile berhasil dihapus');
    }

    private function ensureAlamatTableExists()
    {
        if (!Schema::hasTable('alamat_perusahaan')) {
            try {
                Schema::create('alamat_perusahaan', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('perusahaan_id')->index();
                    $table->string('provinsi')->nullable();
                    $table->string('kota')->nullable();
                    $table->string('kecamatan')->nullable();
                    $table->string('provinsi_id')->nullable();
                    $table->string('kota_id')->nullable();
                    $table->string('kecamatan_id')->nullable();
                    $table->string('desa')->nullable();
                    $table->string('kode_pos')->nullable();
                    $table->text('detail')->nullable();
                    $table->string('label')->nullable();
                    $table->boolean('utama')->default(0);
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                Log::error('Gagal membuat tabel alamat_perusahaan: ' . $e->getMessage());
            }
        } else {
            try {
                if (!Schema::hasColumn('alamat_perusahaan', 'provinsi')) {
                    Schema::table('alamat_perusahaan', function ($table) {
                        $table->string('provinsi')->nullable();
                    });
                }
                if (!Schema::hasColumn('alamat_perusahaan', 'kota')) {
                    Schema::table('alamat_perusahaan', function ($table) {
                        $table->string('kota')->nullable();
                    });
                }
                if (!Schema::hasColumn('alamat_perusahaan', 'kecamatan')) {
                    Schema::table('alamat_perusahaan', function ($table) {
                        $table->string('kecamatan')->nullable();
                    });
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    // ALAMAT PERUSAHAAN
    public function alamat_perusahaan()
    {
        $this->ensureAlamatTableExists();

        $perusahaan = auth()->user()->perusahaan;
        if (!$perusahaan) {
            return redirect()->route('login');
        }

        $alamat_perusahaan = collect();

        if (Schema::hasTable('alamat_perusahaan')) {
            $alamatList = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->get();

            // Jika tabel kosong tapi ada alamat di tabel perusahaans, buat record awal otomatis
            if ($alamatList->isEmpty() && !empty($perusahaan->alamat)) {
                $created = AlamatPerusahaan::create([
                    'perusahaan_id' => $perusahaan->id,
                    'label'         => 'Alamat Utama',
                    'desa'          => $perusahaan->alamat,
                    'detail'        => $perusahaan->alamat,
                    'kota'          => $perusahaan->kota ?? 'Surabaya',
                    'provinsi'      => $perusahaan->provinsi ?? 'Jawa Timur',
                    'kecamatan'     => $perusahaan->kota ?? 'Surabaya',
                    'kode_pos'      => '60111',
                    'utama'         => 1,
                ]);
                $alamatList = collect([$created]);
            }

            $alamat_perusahaan = $alamatList->map(function ($almt) use ($perusahaan) {
                return (object)[
                    'id'        => $almt->id,
                    'label'     => $almt->label ?? ($almt->utama ? 'Alamat Utama' : 'Alamat'),
                    'desa'      => $almt->desa ?? $almt->detail ?? '-',
                    'detail'    => $almt->detail ?? $almt->desa ?? '-',
                    'kecamatan' => (object)['nama' => $almt->kecamatan ?? '-'],
                    'kota'      => (object)['nama' => $almt->kota ?? $perusahaan->kota ?? 'Surabaya'],
                    'provinsi'  => (object)['nama' => $almt->provinsi ?? $perusahaan->provinsi ?? 'Jawa Timur'],
                    'kode_pos'  => $almt->kode_pos ?? '60111',
                    'utama'     => (bool)$almt->utama,
                ];
            });
        }

        $alamatCount = $alamat_perusahaan->count();

        return view('perusahaan.alamat.alamat', compact('perusahaan', 'alamat_perusahaan', 'alamatCount'));
    }

    public function form_alamat()
    {
        $this->ensureAlamatTableExists();

        $provinsis = collect();
        $provincesFile = database_path('data/provinces.json');
        if (file_exists($provincesFile)) {
            $json = json_decode(file_get_contents($provincesFile), true);
            $provinsis = collect($json)->map(function ($item) {
                return (object)[
                    'id'   => (string)$item['id'],
                    'nama' => ucwords(strtolower($item['name'])),
                ];
            });
        }

        return view('perusahaan.alamat.buat-alamat', [
            'provinsis' => $provinsis,
        ]);
    }

    public function getKota($provinsi_id)
    {
        return app(AdminController::class)->getKota($provinsi_id);
    }

    public function getKecamatan($kota_id)
    {
        return app(AdminController::class)->getKecamatan($kota_id);
    }

    public function store_alamat(Request $request)
    {
        $this->ensureAlamatTableExists();

        $perusahaan = Auth::user()->perusahaan;
        if (!$perusahaan) {
            return redirect()->route('login');
        }

        $provinsiNama = $request->provinsi_id ?? $request->provinsi ?? '';
        $kotaNama = $request->kota_id ?? $request->kota ?? '';
        $kecamatanNama = $request->kecamatan_id ?? $request->kecamatan ?? '';

        if (is_numeric($provinsiNama) && file_exists(database_path('data/provinces.json'))) {
            $provinces = json_decode(file_get_contents(database_path('data/provinces.json')), true);
            foreach ($provinces as $p) {
                if ((string)$p['id'] === (string)$provinsiNama) {
                    $provinsiNama = ucwords(strtolower($p['name']));
                    break;
                }
            }
        }

        if (is_numeric($kotaNama) && file_exists(database_path('data/regencies.json'))) {
            $regencies = json_decode(file_get_contents(database_path('data/regencies.json')), true);
            foreach ($regencies as $r) {
                if ((string)$r['id'] === (string)$kotaNama) {
                    $kotaNama = ucwords(strtolower($r['name']));
                    break;
                }
            }
        }

        if (is_numeric($kecamatanNama) && file_exists(database_path('data/districts.json'))) {
            $districts = json_decode(file_get_contents(database_path('data/districts.json')), true);
            if (is_array($districts)) {
                foreach ($districts as $d) {
                    if ((string)$d['id'] === (string)$kecamatanNama) {
                        $kecamatanNama = ucwords(strtolower($d['name']));
                        break;
                    }
                }
            }
        }

        if (Schema::hasTable('alamat_perusahaan')) {
            $count = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->count();
            if ($count >= 5) {
                return redirect()->route('alamat.perusahaan')->with('error', 'Batas maksimal alamat (5 alamat) telah tercapai.');
            }

            $isFirst = $count === 0;

            AlamatPerusahaan::create([
                'perusahaan_id' => $perusahaan->id,
                'label'         => $request->label ?? ($isFirst ? 'Alamat Utama' : 'Alamat'),
                'desa'          => $request->desa,
                'kota'          => $kotaNama ?: $perusahaan->kota,
                'provinsi'      => $provinsiNama ?: $perusahaan->provinsi,
                'kecamatan'     => $kecamatanNama,
                'kode_pos'      => $request->kode_pos,
                'detail'        => $request->detail ?? $request->desa,
                'utama'         => $isFirst ? 1 : 0,
            ]);

            if ($isFirst) {
                $perusahaan->update([
                    'alamat'   => $request->detail ?? $request->desa,
                    'kota'     => $kotaNama ?: $perusahaan->kota,
                    'provinsi' => $provinsiNama ?: $perusahaan->provinsi,
                ]);
            }
        } else {
            $perusahaan->update([
                'alamat'   => $request->detail ?? $request->desa,
                'kota'     => $kotaNama ?: $perusahaan->kota,
                'provinsi' => $provinsiNama ?: $perusahaan->provinsi,
            ]);
        }

        return redirect()->route('alamat.perusahaan')->with('success', 'Alamat berhasil disimpan.');
    }

    public function edit_alamat($id = null)
    {
        $this->ensureAlamatTableExists();

        $perusahaan = Auth::user()->perusahaan;

        $provinsis = collect();
        $provincesFile = database_path('data/provinces.json');
        if (file_exists($provincesFile)) {
            $json = json_decode(file_get_contents($provincesFile), true);
            $provinsis = collect($json)->map(function ($item) {
                return (object)[
                    'id'   => (string)$item['id'],
                    'nama' => ucwords(strtolower($item['name'])),
                ];
            });
        }

        $addr = null;
        if (Schema::hasTable('alamat_perusahaan') && $id) {
            $addr = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->where('id', $id)->first();
        }

        $provinsiNama = $addr->provinsi ?? $perusahaan->provinsi ?? 'Jawa Timur';
        $kotaNama = $addr->kota ?? $perusahaan->kota ?? 'Surabaya';
        $kecamatanNama = $addr->kecamatan ?? $kotaNama;

        $data = (object)[
            'id'           => $addr ? $addr->id : ($id ?? 1),
            'label'        => $addr->label ?? 'Alamat Utama',
            'desa'         => $addr->desa ?? $perusahaan->alamat ?? '',
            'detail'       => $addr->detail ?? $perusahaan->alamat ?? '',
            'provinsi_id'  => $provinsiNama,
            'provinsi'     => (object)['id' => $provinsiNama, 'nama' => $provinsiNama],
            'kota_id'      => $kotaNama,
            'kota'         => (object)['id' => $kotaNama, 'nama' => $kotaNama],
            'kecamatan_id' => $kecamatanNama,
            'kecamatan'    => (object)['id' => $kecamatanNama, 'nama' => $kecamatanNama],
            'kode_pos'     => $addr->kode_pos ?? '60111',
        ];

        return view('perusahaan.alamat.edit', [
            'data'      => $data,
            'provinsis' => $provinsis,
        ]);
    }

    public function update_alamat(Request $request, $id = null)
    {
        $this->ensureAlamatTableExists();

        $perusahaan = Auth::user()->perusahaan;

        $alamatDetail = $request->detail ?? $request->desa ?? $request->alamat ?? $perusahaan->alamat;
        $kotaValue = $request->kota ?? $request->kota_id ?? $perusahaan->kota;
        $provinsiValue = $request->provinsi ?? $request->provinsi_id ?? $perusahaan->provinsi;
        $kecamatanValue = $request->kecamatan ?? $request->kecamatan_id ?? '';

        if (is_numeric($provinsiValue) && file_exists(database_path('data/provinces.json'))) {
            $provinces = json_decode(file_get_contents(database_path('data/provinces.json')), true);
            foreach ($provinces as $p) {
                if ((string)$p['id'] === (string)$provinsiValue) {
                    $provinsiValue = ucwords(strtolower($p['name']));
                    break;
                }
            }
        }

        if (is_numeric($kotaValue) && file_exists(database_path('data/regencies.json'))) {
            $regencies = json_decode(file_get_contents(database_path('data/regencies.json')), true);
            foreach ($regencies as $r) {
                if ((string)$r['id'] === (string)$kotaValue) {
                    $kotaValue = ucwords(strtolower($r['name']));
                    break;
                }
            }
        }

        if (is_numeric($kecamatanValue) && file_exists(database_path('data/districts.json'))) {
            $districts = json_decode(file_get_contents(database_path('data/districts.json')), true);
            if (is_array($districts)) {
                foreach ($districts as $d) {
                    if ((string)$d['id'] === (string)$kecamatanValue) {
                        $kecamatanValue = ucwords(strtolower($d['name']));
                        break;
                    }
                }
            }
        }

        if (Schema::hasTable('alamat_perusahaan') && $id) {
            $addr = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->where('id', $id)->first();
            if ($addr) {
                $addr->update([
                    'label'     => $request->label ?? $addr->label,
                    'desa'      => $request->desa ?? $addr->desa,
                    'kota'      => $kotaValue,
                    'provinsi'  => $provinsiValue,
                    'kecamatan' => $kecamatanValue,
                    'kode_pos'  => $request->kode_pos ?? $addr->kode_pos,
                    'detail'    => $alamatDetail,
                ]);

                if ($addr->utama) {
                    $perusahaan->update([
                        'alamat'   => $alamatDetail,
                        'kota'     => $kotaValue,
                        'provinsi' => $provinsiValue,
                    ]);
                }
                return redirect()->route('alamat.perusahaan')->with('success', 'Alamat berhasil diperbarui.');
            }
        }

        $perusahaan->update([
            'alamat'   => $alamatDetail,
            'kota'     => $kotaValue,
            'provinsi' => $provinsiValue,
        ]);

        return redirect()->route('alamat.perusahaan')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy_alamat($id = null)
    {
        $this->ensureAlamatTableExists();

        $perusahaan = Auth::user()->perusahaan;

        if (Schema::hasTable('alamat_perusahaan') && $id) {
            $addr = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->where('id', $id)->first();
            if ($addr) {
                $wasUtama = $addr->utama;
                $addr->delete();

                $remaining = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->get();
                if ($remaining->isNotEmpty()) {
                    if ($wasUtama) {
                        $newUtama = $remaining->first();
                        $newUtama->update(['utama' => 1]);
                        $perusahaan->update([
                            'alamat'   => $newUtama->desa ?? $newUtama->detail,
                            'kota'     => $newUtama->kota ?? $perusahaan->kota,
                            'provinsi' => $newUtama->provinsi ?? $perusahaan->provinsi,
                        ]);
                    }
                } else {
                    $perusahaan->update([
                        'alamat'   => null,
                        'kota'     => null,
                        'provinsi' => null,
                    ]);
                }

                return redirect()->route('alamat.perusahaan')->with('success', 'Alamat berhasil dihapus.');
            }
        }

        $perusahaan->update([
            'alamat'   => null,
            'kota'     => null,
            'provinsi' => null,
        ]);

        return redirect()->route('alamat.perusahaan')->with('success', 'Alamat berhasil dihapus.');
    }

    public function setUtama($id = null)
    {
        $this->ensureAlamatTableExists();

        $perusahaan = Auth::user()->perusahaan;
        if (!$perusahaan) {
            return back()->with('error', 'Perusahaan tidak ditemukan.');
        }

        if (Schema::hasTable('alamat_perusahaan') && $id) {
            $addr = AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->where('id', $id)->first();
            if ($addr) {
                if ($addr->utama) {
                    $addr->update(['utama' => 0]);
                    $perusahaan->update(['alamat' => null]);
                    return back()->with('success', 'Status alamat utama berhasil dilepas.');
                } else {
                    AlamatPerusahaan::where('perusahaan_id', $perusahaan->id)->update(['utama' => 0]);
                    $addr->update(['utama' => 1]);
                    $perusahaan->update([
                        'alamat'   => $addr->desa ?? $addr->detail,
                        'kota'     => $addr->kota ?? $perusahaan->kota,
                        'provinsi' => $addr->provinsi ?? $perusahaan->provinsi,
                    ]);
                    return back()->with('success', 'Alamat utama berhasil diperbarui.');
                }
            }
        }

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }

    // PELAMAR
    public function pelamar(LowonganPerusahaan $lowongan)
    {
        $lowongan->load('pelamar');
        return view('perusahaan.pelamar.pelamar', [
            "data" => $lowongan,
            "woi"  => PelamarLowongan::all(),
            "exp"  => PelamarLowongan::where('lowongan_id', $lowongan->id)->get(),
        ]);
    }

    // PENGATURAN
    public function pengaturanForm()
    {
        return redirect()->route('profile.perusahaan', ['tab' => 'keamanan']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:3',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah');
    }

    // KANDIDAT AK
    public function kandidat_ak(Request $request)
    {
        $perusahaan = auth()->user()->perusahaan;
        $skills = collect(['Laravel', 'PHP', 'React', 'Vue', 'Python', 'Node.js', 'UI/UX', 'Marketing', 'Accounting', 'Git', 'MySQL']);

        $minAge = Pelamar::selectRaw('MIN(TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE())) as min_age')->value('min_age') ?? 18;
        $maxAge = Pelamar::selectRaw('MAX(TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE())) as max_age')->value('max_age') ?? 40;

        $umurRange = [];
        $step = 5;
        for ($i = $minAge; $i <= $maxAge; $i += $step) {
            $end = $i + $step;
            $umurRange[] = "$i-$end";
        }

        $genders = ['L', 'P'];
        $query = Pelamar::where('kategori', 'kandidat aktif');

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $pelamars = $query->get();

        $hargaPembayarans = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000, 'icon' => 'bitcoin.png'],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000, 'icon' => 'bit2.png'],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000, 'icon' => 'bit3.png'],
        ]);

        return view('perusahaan.kandidat-areakerja', [
            'hargaPembayarans' => $hargaPembayarans,
            'daftarBank'       => DaftarBank::all(),
            'skills'           => $skills,
            'umurRange'        => $umurRange,
            'genders'          => $genders,
            'pelamars'         => $pelamars,
            'perusahaan'       => $perusahaan,
        ]);
    }

    // EVENT
    public function event()
    {
        $events = \App\Models\Event::where('status', '!=', 'draft')->latest('tgl_mulai')->get();
        return view('perusahaan.event.event', compact('events'));
    }

    public function detail($id)
    {
        $event = \App\Models\Event::with('kegiatan')->findOrFail($id);
        return view('perusahaan.event.gabung-event', compact('event'));
    }

    // BERLANGGANAN
    public function halLangganan()
    {
        $user = auth()->user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();
        $hargaLangganan = 1000; // 1.000 koin per tahun

        if (!$perusahaan) {
            abort(404, 'Data perusahaan tidak ditemukan.');
        }

        $hargaPembayarans = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000, 'icon' => 'bitcoin.png'],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000, 'icon' => 'bit2.png'],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000, 'icon' => 'bit3.png'],
        ]);

        return view('perusahaan.langganan.berlangganan', [
            'perusahaan'       => $perusahaan,
            'hargaPembayarans' => $hargaPembayarans,
            'daftarBank'       => DaftarBank::all(),
            'hargaLangganan'   => $hargaLangganan,
        ]);
    }

    public function storeLangganan(Request $request)
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();
        $hargaLangganan = 1000;

        if ($perusahaan->koin_perusahaan < $hargaLangganan) {
            return response()->json([
                'success' => false,
                'error'   => 'koin_kurang',
                'message' => 'Koin tidak cukup untuk berlangganan. Silakan top up koin terlebih dahulu.',
            ], 400);
        }

        $perusahaan->koin_perusahaan -= $hargaLangganan;
        $perusahaan->is_berlangganan = 1;
        $perusahaan->tanggal_berlangganan = Carbon::now();
        $perusahaan->tanggal_expired = Carbon::now()->addYear();
        $perusahaan->save();

        CatatanKoin::create([
            'user_id'      => $user->id,
            'no_referensi' => 'SUB-' . strtoupper(Str::random(8)),
            'pesanan'      => 'Berlangganan Tahunan',
            'dari'         => $perusahaan->nama_perusahaan,
            'sumber_dana'  => 'Pembayaran Langganan',
            'total'        => '-' . $hargaLangganan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berlangganan berhasil! Terima kasih telah berlangganan.',
        ]);
    }

    public function kirimEmail()
    {
        return response()->json(['success' => true]);
    }

    // KANDIDAT SAYA
    public function kandidatSaya(Request $request)
    {
        $search = $request->search;
        $skill  = $request->skill;

        $user = auth()->user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
        $perusahaanId = $perusahaan->id;

        $recruitments = PelamarLowongan::whereIn('status', ['pending', 'diterima', 'ditolak'])
            ->whereHas('lowongan_perusahaan', function ($q) use ($perusahaanId) {
                $q->where('perusahaan_id', $perusahaanId);
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelamar', function ($p) use ($search) {
                    $p->where('nama_pelamar', 'like', "%$search%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('username', 'like', "%$search%");
                        });
                });
            })
            ->with(['pelamar', 'lowongan_perusahaan'])
            ->get();

        return view('perusahaan.kandidat-saya.kandidat-saya', [
            'recruitments' => $recruitments,
            'search'       => $search,
            'skill'        => $skill,
        ]);
    }

    public function destroyRecruitmentPerusahaan($id)
    {
        $recruit = PelamarLowongan::with([
            'pelamar.user',
            'lowongan_perusahaan.perusahaan'
        ])->find($id);

        if ($recruit) {
            $user = $recruit->pelamar->user ?? null;
            $perusahaan = $recruit->lowongan_perusahaan->perusahaan ?? null;
            $recruit->delete();

            if ($user && $perusahaan) {
                Notifikasi::create([
                    'user_id'       => $user->id,
                    'perusahaan_id' => $perusahaan->id,
                    'judul'         => 'Status Recruitment Dibatalkan',
                    'pesan'         => 'Status Recruitment Anda telah dibatalkan oleh Perusahaan.',
                    'expired_at'    => now()->addDays(7),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Recruitment berhasil dibatalkan.');
    }

    public function DiskonFitur()
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Data perusahaan tidak ditemukan.']);
        }

        $nomorAdmin = '6287874732189';
        $pesan = "Halo Admin, Saya Ingin Bertanya Mengenai Diskon Ketika Sudah Berlangganan.\n\n"
            . "Nama Perusahaan: {$perusahaan->nama_perusahaan}\n"
            . "Email Perusahaan: {$user->email}\n"
            . "Terima Kasih.";

        return response()->json([
            'success'      => true,
            'redirect_url' => 'https://wa.me/' . $nomorAdmin . '?text=' . urlencode($pesan),
        ]);
    }

    public function LaporanHarianPekerjaWA()
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Data perusahaan tidak ditemukan.']);
        }

        $nomorAdmin = '6287874732189';
        $pesan = "Halo Admin, Saya Ingin Laporan Harian Pekerja.\n\n"
            . "Nama Perusahaan: {$perusahaan->nama_perusahaan}\n"
            . "Email Perusahaan: {$user->email}\n"
            . "Terima Kasih.";

        return response()->json([
            'success'      => true,
            'redirect_url' => 'https://wa.me/' . $nomorAdmin . '?text=' . urlencode($pesan),
        ]);
    }

    public function CariPekerjaWA(Request $request)
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Data perusahaan tidak ditemukan.']);
        }

        $nomorAdmin = '6287874732189';
        $pesan = "Halo Admin, Saya Ingin Mencari Nama Pekerja.\n\n"
            . "Nama Perusahaan: {$perusahaan->nama_perusahaan}\n"
            . "Email Perusahaan: {$user->email}\n"
            . "Terima Kasih.";

        return response()->json([
            'success'      => true,
            'redirect_url' => 'https://wa.me/' . $nomorAdmin . '?text=' . urlencode($pesan),
        ]);
    }

    public function PekerjaBermasalahWA()
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->first();

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Data perusahaan tidak ditemukan.']);
        }

        $nomorAdmin = '6287874732189';
        $pesan = "Halo Admin, Saya Ingin List Pekerja Yang Bermasalah.\n\n"
            . "Nama Perusahaan: {$perusahaan->nama_perusahaan}\n"
            . "Email Perusahaan: {$user->email}\n"
            . "Terima Kasih.";

        return response()->json([
            'success'      => true,
            'redirect_url' => 'https://wa.me/' . $nomorAdmin . '?text=' . urlencode($pesan),
        ]);
    }

    public function halDaftarPekerja()
    {
        return view('perusahaan.laporan-pekerja');
    }

    public function listPekerjaBermasalah()
    {
        return view('perusahaan.pekerja-bermasalah');
    }

    public function halCariNamaPekerja()
    {
        return view('perusahaan.cari-nama-pekerja');
    }

    public function halLaporanHarianPekerja()
    {
        return view('perusahaan.laporan-harian-pekerja');
    }
}
