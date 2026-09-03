<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use App\Helpers\BrowserPath;

class EmailSubController extends Controller
{
    private function ensureTable()
    {
        if (!Schema::hasTable('email_subscribers')) {
            try {
                Schema::create('email_subscribers', function ($table) {
                    $table->id();
                    $table->string('email')->unique();
                    $table->boolean('is_active')->default(true);
                    $table->unsignedBigInteger('pelamar_id')->nullable();
                    $table->unsignedBigInteger('perusahaan_id')->nullable();
                    $table->timestamps();

                    $table->index('pelamar_id');
                    $table->index('perusahaan_id');
                });
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('email_subscribers table create error: ' . $e->getMessage());
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureTable();
        $request->validate([
            'email' => 'required|email|unique:email_subscribers,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        $email = strtolower($request->email);

        $data = [
            'email' => $email,
            'is_active' => true,
        ];

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->pelamar) {
                $data['pelamar_id'] = $user->pelamar->id;
            }

            if ($user->perusahaan) {
                $data['perusahaan_id'] = $user->perusahaan->id;
            }
        }

        EmailSubscriber::create($data);


        return redirect()->back()->with('success', 'Berhasil berlangganan email.');
    }



    //hal emailnya
    public function halEmail()
    {
        $this->ensureTable();
        $subscribers = EmailSubscriber::with('pelamar.user', 'perusahaan.user')
            ->latest()
            ->get();

        return view('super_admin.EmailSubs.index', compact('subscribers'));
    }


    public function bulkDelete(Request $request)
    {
        $this->ensureTable();
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:email_subscribers,id',
        ], [
            'ids.required' => 'Tidak ada email subscriber yang dipilih.',
            'ids.array' => 'Format data tidak valid.',
            'ids.*.exists' => 'Salah satu email subscriber tidak ditemukan.',
        ]);

        EmailSubscriber::whereIn('id', $request->ids)->delete();

        return redirect()
            ->back()
            ->with('success', count($request->ids) . ' email subscriber berhasil dihapus.');
    }

    public function downloadPdf(Request $request)
    {
        $this->ensureTable();
        if ($request->filled('ids')) {
            $subscribers = EmailSubscriber::with(['pelamar', 'perusahaan'])
                ->whereIn('id', $request->ids)
                ->latest()
                ->get();
        } else {
            // Jika tidak memilih checkbox maka ambil semua
            $subscribers = EmailSubscriber::with(['pelamar', 'perusahaan'])
                ->latest()
                ->get();
        }

        $tanggal = now()->format('d-m-Y');

        $data = [
            'subscribers' => $subscribers,
            'tanggal' => $tanggal,
        ];

        // Render view
        $html = View::make('super_admin.EmailSubs.pdf', $data)->render();

        // Wrapper HTML + Tailwind
        $htmlWithCss = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Email Subscriber</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: "Inter", sans-serif; }
        </style>
    </head>
    <body class="text-[12px] text-black font-sans mx-8 my-6">
        ' . $html . '
    </body>
    </html>
    ';

        // Detect browser
        $browserPath = BrowserPath::detect();
        if (! $browserPath) {
            return response()->json([
                'error' => 'Browser Chrome/Edge tidak ditemukan.'
            ], 500);
        }

        // Generate PDF
        $pdf = Browsershot::html($htmlWithCss)
            ->setOption('executablePath', $browserPath)
            ->noSandbox()
            ->showBackground()
            ->format('A4')
            ->margins(10, 15, 10, 15)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="Email_Subscribers_' . $tanggal . '.pdf"'
            );
    }
}
