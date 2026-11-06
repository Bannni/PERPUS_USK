<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserOnlyMiddleware
{
    /**
     * Handle an incoming request.
     * Prevents admin from accessing user/reader-only pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak! Akun Administrator tidak diizinkan mengakses halaman pembaca / pengguna. Silakan gunakan Dashboard Admin.');
        }

        return $next($request);
    }
}
