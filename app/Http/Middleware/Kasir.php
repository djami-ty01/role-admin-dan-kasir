<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Kasir
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'kasir') {
                return $next($request);
            } else {
                abort(403, 'Akses ditolak! Anda bukan Kasir.');
            }
        } else {
            return redirect()->route('login');
        }
    }
}