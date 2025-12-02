<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:owner,admin',
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::guard('penggunas')->attempt([
            $fieldType => $request->login,
            'password' => $request->password,
            'jenis_pengguna' => $request->role,
        ])) {
            $request->session()->regenerate();

            return redirect()->intended('/beranda');
        }

        return back()->withErrors([
            'login' => 'Email/Username, password, atau role tidak sesuai.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('penggunas')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/hendra')->with('success', 'Anda telah logout.');
    }
}
