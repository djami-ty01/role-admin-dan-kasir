<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'admin') {
                return $next($request);
            } else {
                abort(403, 'Akses ditolak! Anda bukan Admin.');
            }
        } else {
            return redirect()->route('login');
        }
    }
}