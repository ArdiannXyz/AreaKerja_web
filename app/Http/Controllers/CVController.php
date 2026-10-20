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
    // Preview CV di browser
    public function preview(Pelamar $pelamar)
    {
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)); 
        $html = View::make('cv.template', [
            "data" => $pelamar,
            "logoBase64" => $logoBase64,
           "sosmed" => $pelamar->sosmed,
        ])->render();

        $htmlWithCss = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Preview CV Pelamar</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body>
            ' . $html . '
        </body>
        </html>
    ';

        return response($htmlWithCss);
    }


    // Download CV sebagai PDF
    public function downloadCv(Pelamar $pelamar)
    {
        if (auth()->check() && auth()->user()->role === 'perusahaan') {
            $perusahaan = auth()->user()->perusahaan;
            if ($perusahaan) {
                $hasBoughtCv = \App\Models\PembelianCv::where('perusahaan_id', $perusahaan->id)
                    ->where('pelamar_id', $pelamar->id)
                    ->exists();
                $hasRecruited = \App\Models\PelamarLowongan::where('pelamar_id', $pelamar->id)
                    ->whereHas('lowongan_perusahaan', function ($q) use ($perusahaan) {
                        $q->where('perusahaan_id', $perusahaan->id);
                    })
                    ->exists();

                if (!$hasBoughtCv && !$hasRecruited) {
                    $hargaCv = (int) \App\Models\SiteSetting::get('harga_koin_buka_cv', 5);
                    return redirect()->route('perusahaan.kandidat.ak')
                        ->with('error', "Silakan buka CV kandidat terlebih dahulu seharga {$hargaCv} koin.");
                }
            }
        }

        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $html = View::make('cv.template', [
            "data" => $pelamar,
            "pdf" => true,
            'logoBase64' => $logoBase64,
            "sosmed" => $pelamar->sosmed,
        ])->render();


        $htmlWithCss = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>CV Pelamar</title>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body>
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
            ->margins(10, 10, 10, 10)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="cv-' . $pelamar->id . '.pdf"');
    }
}
