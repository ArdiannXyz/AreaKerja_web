<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = strtolower(Auth::user()->role);
        $roles = array_map('strtolower', $roles);

        // ROLE SESUAI → LANJUT
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // ROLE TIDAK SESUAI TENDANG KE DASHBOARD SENDIRI
        return match ($userRole) {
            'pelamar'     => redirect()->route('beranda'),
            'perusahaan'  => redirect()->route('perusahaan.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'super_admin' => redirect()->route('superadmin.dashboard'),
            default       => abort(403),
        };

    }
}
