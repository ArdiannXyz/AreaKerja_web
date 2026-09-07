<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use App\Helpers\BrowserPath; // helper yang kita bikin
use App\Models\Pelamar;
use Illuminate\Support\Facades\View;

class CVController extends Controller
{
    // Helper untuk encode logo & foto profil ke Base64
    private function getBase64Assets(Pelamar $pelamar)
    {
        $logoPath = public_path('images/logo_area_kerja_biru.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/logoarea.png');
        }
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';

        $profileImgBase64 = null;
        if ($pelamar->img_profile && file_exists(public_path('storage/' . $pelamar->img_profile))) {
            $mime = mime_content_type(public_path('storage/' . $pelamar->img_profile)) ?: 'image/jpeg';
            $profileImgBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents(public_path('storage/' . $pelamar->img_profile)));
        }

        return [$logoBase64, $profileImgBase64];
    }

    // Preview CV di browser
    public function preview(Pelamar $pelamar)
    {
        $pelamar->load(['user', 'riwayat_pendidikan', 'pengalaman_kerja', 'pengalaman_organisasi', 'alamat_pelamar']);
        [$logoBase64, $profileImgBase64] = $this->getBase64Assets($pelamar);

        $html = View::make('cv.template', [
            "data"             => $pelamar,
            "logoBase64"       => $logoBase64,
            "profileImgBase64" => $profileImgBase64,
            "sosmed"           => $pelamar->social_links,
        ])->render();

        $htmlWithCss = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Curriculum Vitae - ' . e($pelamar->nama_pelamar ?? $pelamar->user?->username ?? 'Pelamar') . '</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: "Poppins", sans-serif; }
            </style>
        </head>
        <body class="bg-slate-100 p-4 sm:p-8">
            ' . $html . '
        </body>
        </html>
        ';

        return response($htmlWithCss);
    }

    // Download CV sebagai PDF
    public function downloadCv(Pelamar $pelamar)
    {
        $pelamar->load(['user', 'riwayat_pendidikan', 'pengalaman_kerja', 'pengalaman_organisasi', 'alamat_pelamar']);
        [$logoBase64, $profileImgBase64] = $this->getBase64Assets($pelamar);

        $html = View::make('cv.template', [
            "data"             => $pelamar,
            "pdf"              => true,
            "logoBase64"       => $logoBase64,
            "profileImgBase64" => $profileImgBase64,
            "sosmed"           => $pelamar->social_links,
        ])->render();

        $htmlWithCss = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>CV - ' . e($pelamar->nama_pelamar ?? $pelamar->user?->username ?? 'Pelamar') . '</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: "Poppins", sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                @page { margin: 0; size: A4; }
            </style>
        </head>
        <body class="bg-white">
            ' . $html . '
        </body>
        </html>
        ';

        $browserPath = BrowserPath::detect();
        if (!$browserPath) {
            return response()->json([
                "error" => "Browser Chrome/Edge tidak ditemukan. Pastikan sudah terinstall."
            ], 500);
        }

        $pdf = Browsershot::html($htmlWithCss)
            ->setOption('executablePath', $browserPath)
            ->format('A4')
            ->margins(8, 8, 8, 8)
            ->pdf();

        $fileName = 'CV_' . str_replace(' ', '_', $pelamar->nama_pelamar ?? $pelamar->user?->username ?? 'Pelamar') . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
