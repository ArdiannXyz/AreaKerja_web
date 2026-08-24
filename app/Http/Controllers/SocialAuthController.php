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
        $allowedProviders = ['google', 'facebook', 'linkedin'];
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
        $allowedProviders = ['google', 'facebook', 'linkedin'];
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
            // Pengguna Baru - Ambil peran dari session atau default 'pelamar'
            $role = session('social_auth_role', 'pelamar');
            session()->forget('social_auth_role');

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

            Auth::login($user, true);
        }

        // Redirect sesuai peran
        if (Auth::user()->role === 'perusahaan') {
            return redirect('/perusahaan/dashboard')->with('success', 'Berhasil masuk dengan akun ' . ucfirst($provider));
        }

        return redirect('/pelamar/home')->with('success', 'Berhasil masuk dengan akun ' . ucfirst($provider));
    }
}
