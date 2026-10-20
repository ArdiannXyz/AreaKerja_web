<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewReportRequest;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerasiReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $tipe = $request->input('tipe', 'all');
        $alasan = $request->input('alasan');
        $search = $request->input('q');

        $query = Report::with([
            'reporter.pelamar',
            'reporter.perusahaan',
            'lowongan.perusahaan',
            'pelamar.user',
            'reportedUser',
            'admin',
        ])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($tipe !== 'all', fn ($q) => $q->where('tipe', $tipe))
            ->when($alasan, fn ($q) => $q->where('alasan', $alasan))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->whereHas('lowongan', function ($lq) use ($search) {
                        $lq->where('nama', 'like', "%{$search}%");
                    })->orWhereHas('lowongan.perusahaan', function ($pq) use ($search) {
                        $pq->where('nama_perusahaan', 'like', "%{$search}%");
                    })->orWhereHas('pelamar', function ($pelq) use ($search) {
                        $pelq->where('nama_pelamar', 'like', "%{$search}%")
                            ->orWhere('telepon_pelamar', 'like', "%{$search}%");
                    })->orWhereHas('reporter', function ($rq) use ($search) {
                        $rq->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('reportedUser', function ($ruq) use ($search) {
                        $ruq->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            })
            ->latest();

        $baseCountQuery = Report::query()
            ->when($tipe !== 'all', fn ($q) => $q->where('tipe', $tipe));

        $counts = [
            'pending'   => (clone $baseCountQuery)->pending()->count(),
            'resolved'  => (clone $baseCountQuery)->where('status', Report::STATUS_RESOLVED)->count(),
            'dismissed' => (clone $baseCountQuery)->where('status', Report::STATUS_DISMISSED)->count(),
            'all'       => (clone $baseCountQuery)->count(),
            'tipe_lowongan' => Report::where('tipe', Report::TIPE_LOWONGAN)->count(),
            'tipe_pelamar'  => Report::where('tipe', Report::TIPE_PELAMAR)->count(),
        ];

        $alasanOptions = match ($tipe) {
            Report::TIPE_PELAMAR  => Report::ALASAN_PELAMAR,
            Report::TIPE_LOWONGAN => Report::ALASAN_LOWONGAN,
            default               => Report::ALASAN,
        };

        return view($this->view('index'), [
            'reports'       => $query->paginate(15)->withQueryString(),
            'counts'        => $counts,
            'statusFilter'  => $status,
            'tipeFilter'    => $tipe,
            'alasanFilter'  => $alasan,
            'search'        => $search,
            'alasanOptions' => $alasanOptions,
            'routes'        => $this->routes(),
            'isSuper'       => $this->isSuper(),
        ]);
    }

    public function show(Report $report): View
    {
        $report->load([
            'reporter.pelamar',
            'reporter.perusahaan',
            'lowongan.perusahaan.user',
            'pelamar.user',
            'reportedUser',
            'admin',
        ]);

        if ($report->isPelamar()) {
            $relatedCount = Report::where('pelamar_id', $report->pelamar_id)->count();
            $relatedPending = Report::where('pelamar_id', $report->pelamar_id)
                ->pending()
                ->where('id', '!=', $report->id)
                ->count();
        } else {
            $relatedCount = Report::where('lowongan_id', $report->lowongan_id)->count();
            $relatedPending = Report::where('lowongan_id', $report->lowongan_id)
                ->pending()
                ->where('id', '!=', $report->id)
                ->count();
        }

        return view($this->view('show'), [
            'report'         => $report,
            'relatedCount'   => $relatedCount,
            'relatedPending' => $relatedPending,
            'routes'         => $this->routes(),
            'alasanOptions'  => $report->isPelamar() ? Report::ALASAN_PELAMAR : Report::ALASAN_LOWONGAN,
            'isSuper'        => $this->isSuper(),
        ]);
    }

    public function review(ReviewReportRequest $request, Report $report, ReportService $service): RedirectResponse
    {
        $action = $request->validated('action');

        $service->review(
            $report,
            $request->user(),
            $action,
            $request->validated('admin_notes'),
            $request->validated('alasan_freeze')
        );

        if ($report->isPelamar()) {
            $messages = [
                'dismiss'         => 'Laporan terhadap pelamar ditolak.',
                'warn'            => 'Peringatan resmi berhasil dikirim ke akun pelamar.',
                'revoke_kandidat' => 'Status kandidat aktif pelamar berhasil dicabut.',
                'freeze'          => 'Akun pelamar berhasil dibekukan.',
                'unfreeze'        => 'Pembekuan akun pelamar berhasil dicabut.',
            ];
        } else {
            $messages = [
                'dismiss' => 'Laporan ditolak. Lowongan tetap ditayangkan.',
                'hide'    => 'Lowongan disembunyikan dari listing publik.',
                'warn'    => 'Peringatan dikirim ke perusahaan.',
                'freeze'  => 'Akun perusahaan dibekukan dan lowongan disembunyikan.',
                'restore' => 'Lowongan dipulihkan ke listing publik.',
            ];
        }

        return redirect()
            ->route($this->routes()['show'], $report)
            ->with('success', $messages[$action] ?? 'Aksi moderasi berhasil.');
    }

    protected function isSuper(): bool
    {
        return strtolower((string) optional(auth()->user())->role) === 'super_admin';
    }

    protected function view(string $name): string
    {
        return $this->isSuper()
            ? "super_admin.reports.{$name}"
            : "admin.reports.{$name}";
    }

    protected function routes(): array
    {
        if ($this->isSuper()) {
            return [
                'index'  => 'superadmin.reports.index',
                'show'   => 'superadmin.reports.show',
                'review' => 'superadmin.reports.review',
            ];
        }

        return [
            'index'  => 'admin.reports.index',
            'show'   => 'admin.reports.show',
            'review' => 'admin.reports.review',
        ];
    }
}
