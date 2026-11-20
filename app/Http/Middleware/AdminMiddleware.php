<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Akses ditolak! Halaman administrator ini dilindungi dan hanya dapat diakses oleh Administrator.');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('catalog')->with('error', 'Akses ditolak! Anda tidak memiliki izin Administrator.');
        }

        return $next($request);
    }
}
