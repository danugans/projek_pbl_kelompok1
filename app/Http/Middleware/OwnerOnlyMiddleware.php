<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerOnlyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek: harus login via guard 'penggunas' dan role = 'owner'
        $user = Auth::guard('penggunas')->user();
        if (!$user || $user->jenis_pengguna !== 'owner') {
            return redirect('/beranda')
                ->with('error', 'Akses ditolak. Hanya owner yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
