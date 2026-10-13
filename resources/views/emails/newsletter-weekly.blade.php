<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lowongan Kerja Terbaru - AreaKerja</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f3f4f6; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0054a6 0%, #003366 100%); padding: 36px 30px; text-align: center;">
                            <div style="font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                Area<span style="color: #ff7a00;">Kerja</span>
                            </div>
                            <div style="display: inline-block; background-color: rgba(255, 255, 255, 0.15); color: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 12px;">
                                Weekly Newsletter
                            </div>
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 16px 0 6px 0; line-height: 1.3;">
                                Rekomendasi Lowongan Pilihan Minggu Ini
                            </h1>
                            <p style="color: rgba(255, 255, 255, 0.85); font-size: 14px; margin: 0; line-height: 1.5;">
                                Temukan peluang karir impian Anda berikutnya dan segera kirimkan lamaran!
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px 24px;">
                            <p style="margin: 0 0 20px 0; font-size: 14px; color: #4b5563; line-height: 1.6;">
                                Halo Rekan Pencari Kerja,<br>
                                Berikut beberapa lowongan kerja aktif dan terverifikasi terbaru yang baru saja dibuka di <strong>AreaKerja.com</strong>:
                            </p>

                            <!-- Lowongan Cards Loop -->
                            @if(isset($lowongans) && $lowongans->count() > 0)
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    @foreach($lowongans as $item)
                                        <tr>
                                            <td style="padding-bottom: 16px;">
                                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border: 1px solid #e5e7eb; border-radius: 12px; background-color: #fafafa; padding: 18px 20px;">
                                                    <tr>
                                                        <td>
                                                            <!-- Company & Type -->
                                                            <div style="font-size: 12px; color: #ff7a00; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                                                {{ $item->perusahaan->nama_perusahaan ?? 'Perusahaan Terverifikasi' }}
                                                            </div>
                                                            <!-- Job Title -->
                                                            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #111827; line-height: 1.3;">
                                                                {{ $item->nama }}
                                                            </h3>
                                                            
                                                            <!-- Badges / Details -->
                                                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 14px; line-height: 1.4;">
                                                                📍 {{ $item->perusahaan->kota ?? ($item->alamat ?? 'Indonesia') }}
                                                                @if(!empty($item->jenis))
                                                                    &nbsp;•&nbsp; 💼 {{ $item->jenis }}
                                                                @endif
                                                                @if(!empty($item->gaji_awal) && !empty($item->gaji_akhir))
                                                                    &nbsp;•&nbsp; 💰 Rp {{ number_format($item->gaji_awal, 0, ',', '.') }} - Rp {{ number_format($item->gaji_akhir, 0, ',', '.') }}
                                                                @elseif(!empty($item->label_gaji))
                                                                    &nbsp;•&nbsp; 💰 {{ $item->label_gaji }}
                                                                @endif
                                                            </div>

                                                            <!-- Action Button -->
                                                            <div>
                                                                <a href="{{ route('lowongan.show', $item->slug) }}" target="_blank" style="display: inline-block; background-color: #0054a6; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 8px;">
                                                                    Lihat Detail & Lamar &rarr;
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <p style="font-size: 14px; color: #6b7280; text-align: center; padding: 20px 0;">
                                    Saat ini belum ada lowongan baru yang tersedia. Silakan cek berkala di situs kami.
                                </p>
                            @endif

                            <!-- CTA View All -->
                            <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px dashed #e5e7eb;">
                                <a href="{{ url('/') }}" target="_blank" style="display: inline-block; background-color: #ff7a00; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 12px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(255, 122, 0, 0.25);">
                                    Jelajahi Ratusan Lowongan Lainnya
                                </a>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer & Unsubscribe Info -->
                    <tr>
                        <td style="background-color: #f9fafb; border-top: 1px solid #f3f4f6; padding: 26px 24px; text-align: center;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #6b7280; line-height: 1.5;">
                                Anda menerima email ini karena alamat <strong>{{ $subscriber->email }}</strong> terdaftar dalam buletin newsletter lowongan kerja di <strong>AreaKerja.com</strong>.
                            </p>
                            
                            <p style="margin: 0 0 14px 0; font-size: 12px; color: #9ca3af; line-height: 1.5;">
                                Tidak ingin lagi menerima email rekomendasi lowongan kerja dari kami?
                            </p>

                            <!-- One-click signed unsubscribe button for guests and registered users alike -->
                            <div style="margin-bottom: 16px;">
                                <a href="{{ $unsubscribeUrl }}" target="_blank" style="display: inline-block; font-size: 12px; color: #dc2626; text-decoration: underline; font-weight: 600;">
                                    Berhenti Berlangganan (Unsubscribe)
                                </a>
                                <span style="font-size: 11px; color: #9ca3af; margin-left: 6px;">(Tanpa perlu login)</span>
                            </div>

                            <div style="font-size: 11px; color: #9ca3af;">
                                &copy; {{ date('Y') }} AreaKerja.com. Hak cipta dilindungi undang-undang.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

