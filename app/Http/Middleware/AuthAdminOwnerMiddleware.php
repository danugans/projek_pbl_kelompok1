<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdminOwnerMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('penggunas')->user();

        if (!$user || !in_array($user->jenis_pengguna, ['admin', 'owner'])) {
            return redirect('/hendra')->with('error', 'Silakan login sebagai admin/owner.');
        }

        return $next($request);
    }
}


