<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Login via Email & Password untuk Mobile App (Flutter).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi yang Anda masukkan salah.',
            ], 401);
        }

        if ($user->status != 0) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda telah dinonaktifkan.',
            ], 403);
        }

        $deviceName = $request->device_name ?? 'mobile-app';
        $token = $user->createToken($deviceName)->plainTextToken;

        // Load profile
        $profile = null;
        if ($user->role === 'pelamar') {
            $profile = $user->pelamar;
        } elseif ($user->role === 'perusahaan') {
            $profile = $user->perusahaan;
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'token'   => $token,
                'user'    => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'verified' => (bool)$user->verified,
                ],
                'profile' => $profile,
            ],
        ]);
    }

    /**
     * Registrasi Pelamar baru dari Mobile.
     */
    public function registerPelamar(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|min:3',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama'     => 'required|string|max:255',
            'telepon'  => 'required|string|max:20',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelamar',
            'verified' => 1,
            'status'   => 0,
        ]);

        $pelamar = $user->pelamar()->create([
            'nama_pelamar'    => $request->nama,
            'telepon_pelamar' => $request->telepon,
            'kategori'        => 'pelamar',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran pelamar berhasil.',
            'data'    => [
                'token'   => $token,
                'user'    => $user,
                'profile' => $pelamar,
            ],
        ], 201);
    }

    /**
     * Get Current Authenticated User Profile.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'pelamar') {
            $user->load([
                'pelamar.pengalaman_kerja',
                'pelamar.riwayat_pendidikan',
            ]);
        } elseif ($user->role === 'perusahaan') {
            $user->load(['perusahaan']);
        }

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    /**
     * Logout & Revoke Current Token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar (logout).',
        ]);
    }
}
