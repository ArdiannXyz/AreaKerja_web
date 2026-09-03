<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP - AreaKerja</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            -webkit-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f1f5f9;
            padding: 40px 10px;
        }
        .main-card {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 540px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #00509d;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 6px 0 0 0;
            color: #bfdbfe;
            font-size: 13px;
        }
        .content {
            padding: 35px 40px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .otp-container {
            background: #f8fafc;
            border: 2px dashed #93c5fd;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .otp-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            color: #00509d;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #00509d;
            margin: 0;
            padding-left: 8px;
        }
        .warning-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 12px;
            color: #92400e;
            line-height: 1.5;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .footer a {
            color: #00509d;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-card">
            <!-- Header -->
            <div class="header">
                <h1>areakerja.com</h1>
                <p>Platform Lowongan Kerja & Manajemen Talenta</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    Halo, {{ $user->username ?? $user->name ?? 'Pengguna AreaKerja' }} 👋
                </div>

                <div class="text">
                    Kami menerima permintaan verifikasi untuk pemulihan kata sandi akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan:
                </div>

                <!-- OTP Code Box -->
                <div class="otp-container">
                    <div class="otp-label">Kode Verifikasi OTP Anda</div>
                    <div class="otp-code">{{ $otp }}</div>
                </div>

                <div class="text" style="font-size: 13px; color: #64748b; margin-bottom: 0;">
                    Masukkan kode 6 digit ini pada halaman verifikasi di aplikasi atau situs web AreaKerja.
                </div>

                <!-- Warning Notice -->
                <div class="warning-box">
                    <strong>Penting:</strong> Kode ini hanya berlaku selama <strong>10 menit</strong>. Jangan pernah membagikan kode verifikasi ini kepada siapapun demi keamanan akun Anda.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px 0;">Jika Anda tidak merasa meminta kode ini, Anda dapat mengabaikan email ini dengan aman.</p>
                <p style="margin: 0;">&copy; {{ date('Y') }} <strong>AreaKerja</strong>. Hak cipta dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
