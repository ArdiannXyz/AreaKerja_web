<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            'username'     => $request->username,
            'nama_lengkap' => $request->nama,
            'email'        => $request->email,
            'telepon'      => $request->telepon,
            'password'     => Hash::make($request->password),
            'role'         => 'pelamar',
            'verified'     => 1,
            'status'       => 0,
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
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini tidak cocok.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui.',
        ]);
    }

    /**
     * Request Forgot Password OTP
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate 6 digit OTP
        $otp = (string) rand(100000, 999999);
        $cacheKey = 'password_reset_otp_' . $request->email;

        // Store OTP in cache for 15 minutes
        Cache::put($cacheKey, $otp, now()->addMinutes(15));

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi (OTP) reset kata sandi telah dikirim ke email Anda.',
            // For testing/mocking in development, we include otp if APP_DEBUG is true
            'debug_otp' => config('app.debug') ? $otp : null,
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|string|size:6',
        ]);

        $cacheKey = 'password_reset_otp_' . $request->email;
        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau telah kedaluwarsa.',
            ], 422);
        }

        // Generate Reset Token for next step
        $resetToken = Str::random(60);
        Cache::put('password_reset_token_' . $request->email, $resetToken, now()->addMinutes(15));

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP valid.',
            'data'    => [
                'reset_token' => $resetToken,
            ],
        ]);
    }

    /**
     * Reset Password using Token/OTP
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'reset_token'           => 'required|string',
            'password'              => 'required|min:8|confirmed',
        ]);

        $cachedToken = Cache::get('password_reset_token_' . $request->email);

        if (!$cachedToken || $cachedToken !== $request->reset_token) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset kata sandi tidak valid atau telah kedaluwarsa.',
            ], 422);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear cache
        Cache::forget('password_reset_otp_' . $request->email);
        Cache::forget('password_reset_token_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil direset. Silakan masuk menggunakan kata sandi baru.',
        ]);
    }

    /**
     * Delete Account Permanently (Google Play & App Store Compliance)
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // Revoke all tokens
        $user->tokens()->delete();

        // Delete associated records
        if ($user->pelamar) {
            $user->pelamar->pengalaman_kerja()->delete();
            $user->pelamar->riwayat_pendidikan()->delete();
            $user->pelamar()->delete();
        }

        if ($user->perusahaan) {
            $user->perusahaan->pasanglowongan()->delete();
            $user->perusahaan()->delete();
        }

        $user->notifikasis()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun Anda dan seluruh data terkait telah berhasil dihapus secara permanen.',
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
