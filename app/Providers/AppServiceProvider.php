<?php

namespace App\Providers;

use App\Models\DaftarBank;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_contains(config('app.url'), 'https://') || request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share notifikasi ke semua view
        View::composer('*', function ($view) {
            if (!Auth::check()) {
                $notifikasis = collect();
                $jumlahBelumDibaca = 0;
            } else {
                $user = Auth::user();
                $notifikasis = Notifikasi::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $jumlahBelumDibaca = Notifikasi::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();
            }

            $view->with([
                'global_notifikasis'       => $notifikasis,
                'global_notifikasi_unread' => $jumlahBelumDibaca,
            ]);
        });

        // Social links collection from SiteSettingService
        View::composer('layouts.footer', function ($view) {
            $view->with('socialLinks', \App\Services\SiteSettingService::getSocials());
        });

        // Top up data khusus perusahaan
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'perusahaan') {
                $hargaPembayarans = collect([
                    (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000, 'icon' => 'bitcoin.png'],
                    (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000, 'icon' => 'bit2.png'],
                    (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000, 'icon' => 'bit3.png'],
                ]);
                $daftarBank = DaftarBank::all();

                $view->with([
                    'hargaPembayarans' => $hargaPembayarans,
                    'daftarBank'       => $daftarBank,
                ]);
            }
        });

        // Sidebar Admin
        View::composer('admin.sidebar.index', function ($view) {
            $view->with('provinsis', collect());
        });

        // Perusahaan Layout
        View::composer('layouts.index-perusahaan', function ($view) {
            $user = auth()->user();
            $view->with('perusahaan', $user ? $user->perusahaan : null);
        });
    }
}
