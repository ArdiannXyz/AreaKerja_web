<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role == 'super_admin') {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == 'pelamar') {
                return redirect('/');
            } elseif ($user->role == 'perusahaan') {
                return redirect()->route('perusahaan.dashboard');
            }
        }


        return $next($request);
    }
}
