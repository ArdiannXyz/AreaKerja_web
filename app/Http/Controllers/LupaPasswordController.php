<?php

namespace App\Http\Controllers;

use App\Models\PasswordVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LupaPasswordController extends Controller
{
    private function ensureTableExists()
    {
        if (!Schema::hasTable('password_verifications')) {
            try {
                Schema::create('password_verifications', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                    $table->string('email')->index();
                    $table->string('otp', 10);
                    $table->string('token', 64)->unique();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                Log::error('Gagal create password_verifications: ' . $e->getMessage());
            }
        }
    }

    public function resendOtp(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        $otp = rand(100000, 999999);

        // AMANKAN 👉 token TIDAK diganti
        $verif = PasswordVerification::where('email', $request->email)->first();

        if (!$verif) {
            return response()->json(['message' => 'Token tidak ditemukan'], 404);
        }

        $verif->update([
            'otp' => $otp
        ]);

        try {
            Mail::send('emails.otp-verification', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode Verifikasi OTP - AreaKerja');
            });
        } catch (\Throwable $e) {
            Log::info("OTP untuk {$user->email}: {$otp} | Error: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'OTP telah dikirim ulang.'
        ]);
    }


    public function showEmailForm_pelamar()
    {
        $this->ensureTableExists();
        return view('non-user.auth.verifikasi');
    }

    //kirim otp ke email
    public function sendOtp(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Jika user TIDAK ditemukan, munculkan SweetAlert
        if (!$user) {
            return back()->with('alert', [
                'title' => 'Email Tidak Ditemukan!',
                'text'  => 'Email yang Anda masukkan tidak terdaftar.',
                'icon'  => 'error'
            ]);
        }

        // --- Jika user ditemukan, lanjut OTP ---
        $otp = rand(100000, 999999);
        $token = Str::random(64);

        PasswordVerification::updateOrCreate(
            ['email' => $request->email],
            [
                'user_id' => $user->id,
                'otp' => $otp,
                'token' => $token
            ]
        );

        try {
            Mail::send('emails.otp-verification', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode Verifikasi OTP - AreaKerja');
            });
        } catch (\Throwable $e) {
            Log::info("OTP untuk {$user->email}: {$otp} | Error: " . $e->getMessage());
        }

        return redirect()->route('password.otp.form.pelamar', $token)
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtpForm_pelamar($token)
    {
        $verif = PasswordVerification::where('token', $token)->firstOrFail();
        $email = $verif->email;
        return view('non-user.auth.verifikasicode', compact('email', 'token'));
    }

    //proses verif otp
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'token' => 'required',
        ]);

        $verif = PasswordVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('token', $request->token)
            ->first();

        if (!$verif) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        session(['otp_verified' => true]);

        // FIX: redirect benar
        return redirect(
            url('/reset-password/' . $verif->token) . '?email=' . urlencode($verif->email)
        );
    }


    //form lupa pwnya
    public function showResetForm_pelamar($token, Request $request)
    {
        return view('non-user.auth.verif-lupa-sandi', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }


    //simpan pw baru

    public function resetPassword(Request $request)
    {
        // Pastikan user sudah melewati verifikasi OTP terlebih dahulu
        if (!session('otp_verified')) {
            return redirect()->route('verifikasi_pelamar')
                ->withErrors(['otp' => 'Silakan verifikasi OTP terlebih dahulu.']);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string',
            'token' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {

            $password = $request->password;
            $confirm  = $request->password_confirmation;

            // 1. Cek konfirmasi tidak cocok
            if ($password !== $confirm) {
                $validator->errors()->add('password', 'Konfirmasi kata sandi tidak cocok.');
            }

            // 2. Minimal 8 karakter
            if (strlen($password) < 8)
                $validator->errors()->add('password', 'Password harus minimal 8 karakter.');

            // 3. Huruf besar
            if (!preg_match('/[A-Z]/', $password))
                $validator->errors()->add('password', 'Password harus mengandung minimal satu huruf besar.');

            // 4. Huruf kecil
            if (!preg_match('/[a-z]/', $password))
                $validator->errors()->add('password', 'Password harus mengandung minimal satu huruf kecil.');

            // 5. Angka
            if (!preg_match('/[0-9]/', $password))
                $validator->errors()->add('password', 'Password harus mengandung minimal satu angka.');

            // 6. Simbol
            if (!preg_match('/[@$!%*?&#]/', $password))
                $validator->errors()->add('password', 'Password harus mengandung minimal satu simbol.');
        });


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // Cek token
        $verif = PasswordVerification::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$verif) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.'
            ], 404);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token setelah dipakai
        $verif->delete();
        Auth::logout();

        return response()->json([
            'success' => true
        ]);
    }







    //PERUSAHAAN
    public function showEmailForm_perusahaan()
    {
        return view('perusahaan.auth.verif');
    }

    //kirim otp ke email
    public function sendOtp_perusahaan(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek apakah email terdaftar sebelum lanjut
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        $otp = rand(100000, 999999);
        $token = Str::random(64);

        PasswordVerification::UpdateOrcreate(
            ['email' => $request->email],
            [
                'user_id' => $user->id,
                'otp' => $otp,
                'token' => $token
            ]
        );

        //kirim email otp
        try {
            Mail::send('emails.otp-verification', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode Verifikasi OTP - AreaKerja');
            });
        } catch (\Throwable $e) {
            Log::info("OTP Perusahaan untuk {$user->email}: {$otp} | Error: " . $e->getMessage());
        }

        return redirect()->route('password.otp.form.perusahaan', $token)->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtpForm_perusahaan($token)
    {
        $verif = PasswordVerification::where('token', $token)->firstOrFail();
        $email = $verif->email;
        return view('perusahaan.auth.verif-otp', compact('email', 'token'));
    }

    //proses verif otp
    public function verifyOtp_perusahaan(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'token' => 'required',
        ]);

        $verif = PasswordVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('token', $request->token)
            ->first();

        if (!$verif) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }
        return redirect()->route('password.reset.form.perusahaan', [
            'token' => $verif->token,
            'email' => $verif->email
        ]);
    }

    //form lupa pwnya
    public function showResetForm_perusahaan(Request $request, $token = null)
    {
        return view('perusahaan.auth.verif-lupapw', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    //simpan pw baru
    public function resetPassword_perusahaan(Request $request)
    {

        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // huruf besar
                'regex:/[a-z]/',      // huruf kecil
                'regex:/[0-9]/',      // angka
                'regex:/[@$!%*?&#]/', // simbol
            ],
            'token'    => 'required',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $verif = PasswordVerification::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$verif) {
            return back()->withErrors(['token' => 'Token tidak valid.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        //hapus klo berhasil
        $verif->delete();
        Auth::logout();

        return redirect()->route('login_perusahaan')->with('success', 'Password berhasil diubah.');
    }
}
