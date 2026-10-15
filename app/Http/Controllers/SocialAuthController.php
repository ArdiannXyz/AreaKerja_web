<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private function getDriverName(string $provider): string
    {
        $provider = strtolower($provider);
        if ($provider === 'linkedin') {
            return 'linkedin-openid';
        }
        return $provider;
    }

    public function redirect(Request $request, $provider)
    {
        $allowedProviders = ['google', 'facebook'];
        if (!in_array(strtolower($provider), $allowedProviders)) {
            return redirect()->route('login')->with('error', 'Provider otentikasi sosial tidak didukung.');
        }

        // Simpan peran (role) target jika dikirim melalui query parameter
        if ($request->has('role')) {
            session(['social_auth_role' => $request->query('role')]);
        }

        $driver = $this->getDriverName($provider);
        $clientId = config("services.{$driver}.client_id") ?? config("services.{$provider}.client_id");

        if (empty($clientId) || str_contains($clientId, 'your-')) {
            $provName = strtoupper($provider);
            return redirect()->back()->with('error', "Otentikasi {$provider} belum dapat digunakan karena {$provName}_CLIENT_ID & {$provName}_CLIENT_SECRET belum diisi pada file .env.");
        }

        try {
            return Socialite::driver($driver)->redirect();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke ' . ucfirst($provider) . ': Kunci API belum dikonfigurasi pada .env.');
        }
    }

    public function callback(Request $request, $provider)
    {
        $allowedProviders = ['google', 'facebook'];
        if (!in_array(strtolower($provider), $allowedProviders)) {
            return redirect()->route('login')->with('error', 'Provider otentikasi sosial tidak didukung.');
        }

        $driver = $this->getDriverName($provider);

        try {
            $socialUser = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal memproses otentikasi dengan ' . ucfirst($provider) . '.');
        }

        $providerIdField = strtolower($provider) . '_id';
        $email = $socialUser->getEmail();
        $socialId = $socialUser->getId();
        $name = $socialUser->getName() ?? $socialUser->getNickname() ?? 'Pengguna ' . ucfirst($provider);
        $avatar = $socialUser->getAvatar();

        // Cari berdasarkan social ID atau email
        $user = User::where($providerIdField, $socialId);
        if ($email) {
            $user = $user->orWhere('email', $email);
        }
        $user = $user->first();

        if ($user) {
            // Update social ID dan avatar jika belum terhubung
            $user->{$providerIdField} = $socialId;
            if (empty($user->avatar_social)) {
                $user->avatar_social = $avatar;
            }
            $user->save();

            Auth::login($user, true);
        } else {
            // Pengguna Baru
            // 1. Cek apakah ada role yang sudah ditentukan dari session (misal dari tombol register pelamar/perusahaan)
            $role = session('social_auth_role');
            session()->forget('social_auth_role');

            if (empty($role) || !in_array($role, ['pelamar', 'perusahaan'])) {
                // Simpan data sosial sementara di session dan arahkan ke halaman pilih peran
                session([
                    'social_temp_user' => [
                        'provider'         => $provider,
                        'providerIdField'  => $providerIdField,
                        'socialId'         => $socialId,
                        'name'             => $name,
                        'email'            => $email,
                        'avatar'           => $avatar,
                    ]
                ]);

                return redirect()->route('social.pilih-role');
            }

            // Jika role sudah jelas dari awal
            $user = $this->createSocialUser($role, $providerIdField, $socialId, $name, $email, $avatar);
            Auth::login($user, true);
        }

        // Redirect sesuai peran
        if (Auth::user()->role === 'perusahaan') {
            return redirect('/perusahaan/dashboard')->with('success', 'Berhasil masuk dengan akun ' . ucfirst($provider));
        }

        return redirect('/pelamar/home')->with('success', 'Berhasil masuk dengan akun ' . ucfirst($provider));
    }

    public function pilihRole()
    {
        $tempUser = session('social_temp_user');
        if (!$tempUser) {
            return redirect()->route('login')->with('error', 'Sesi pendaftaran sosial telah kedaluwarsa. Silakan coba lagi.');
        }

        return view('auth.social-pilih-role', compact('tempUser'));
    }

    public function simpanRole(Request $request)
    {
        $role = $request->input('role', $request->query('role'));
        if (!in_array($role, ['pelamar', 'perusahaan'])) {
            return redirect()->route('social.pilih-role')->with('error', 'Silakan pilih salah satu peran.');
        }

        $tempUser = session('social_temp_user');
        if (!$tempUser) {
            return redirect()->route('login')->with('error', 'Sesi pendaftaran sosial telah kedaluwarsa. Silakan coba lagi.');
        }

        session()->forget('social_temp_user');

        $user = $this->createSocialUser(
            $role,
            $tempUser['providerIdField'],
            $tempUser['socialId'],
            $tempUser['name'],
            $tempUser['email'],
            $tempUser['avatar']
        );

        Auth::login($user, true);

        if ($role === 'perusahaan') {
            return redirect('/perusahaan/dashboard')->with('success', 'Selamat datang! Pendaftaran akun Perusahaan berhasil.');
        }

        return redirect('/pelamar/home')->with('success', 'Selamat datang! Pendaftaran akun Pelamar berhasil.');
    }

    private function createSocialUser(string $role, string $providerIdField, string $socialId, string $name, ?string $email, ?string $avatar): User
    {
        $username = Str::slug($name) . rand(10, 99);
        while (User::where('username', $username)->exists()) {
            $username = Str::slug($name) . rand(100, 999);
        }

        $user = User::create([
            'username'        => $username,
            'nama_lengkap'    => $name,
            'email'           => $email ?? ($username . '@social.local'),
            'password'        => Hash::make(Str::random(16)),
            'role'            => $role,
            'status'          => '0',
            'verified'        => 1,
            $providerIdField  => $socialId,
            'avatar_social'   => $avatar,
        ]);

        if ($role === 'perusahaan') {
            Perusahaan::create([
                'user_id'            => $user->id,
                'nama_perusahaan'    => $name,
                'email'              => $user->email,
                'slug'               => Str::slug($name) . '-' . time(),
                'verified'           => 1,
                'status'             => '0',
            ]);
        } else {
            Pelamar::create([
                'user_id'            => $user->id,
                'nama_lengkap'       => $name,
                'email'              => $user->email,
                'verified'           => 1,
                'status'             => '0',
            ]);
        }

        return $user;
    }
}
