<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PelangganAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'nama' => 'required|string|max:255|min:2',
            'username' => 'required|string|max:255|min:2|unique:pelanggan,username',
            'email' => 'required|email|unique:pelanggan,email',
            'password' => [
                'required',
                'min:6',
                'regex:/[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
            'confirm_password' => 'required|same:password',
            'nomor_telepon' => 'required|numeric|unique:pelanggan,nomor_telepon',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama tidak boleh 1 karakter',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar dan karakter spesial.',
            'confirm_password.required' => 'Konfirmasi password wajib diisi.',
            'confirm_password.same' => 'Konfirmasi password tidak sama.',
            'nomor_telepon.required' => 'Nomor HP wajib diisi.',
            'nomor_telepon.numeric' => 'Nomor HP harus berupa angka.',
            'nomor_telepon.unique' => 'Nomor HP sudah digunakan.',
        ]);



        $pelanggan = Pelanggan::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
        ]);


        Log::info('Pelanggan registered successfully', [
            'pelanggan_id' => $pelanggan->id,
            'email' => $pelanggan->email,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ], [
            'username.required' => 'Username atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $login_type = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::guard('pelanggan')->attempt([$login_type => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            Log::info('User logged in successfully', [
                'user_id' => Auth::guard('pelanggan')->id(),
                'username_or_email' => $request->username,
            ]);

            return redirect()->intended('daftarmeja');
        }

        Log::warning('Login attempt failed', [
            'username_or_email' => $request->username,
        ]);

        return back()->withErrors([
            'login' => 'Username / Email atau password salah.',
        ])->withInput();
    }


    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pelanggan,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan.',
        ]);

        // Bisa juga generate token dan kirim email, tapi untuk simpel, langsung redirect
        return redirect()->route('password.reset', ['email' => $request->email]);
    }

    public function showResetForm($email)
    {
        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pelanggan,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Pelanggan::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }



    public function logout(Request $request)
    {
        Auth::guard('pelanggan')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }





    public function index(Request $request)
    {
        $keyword = $request->input('search');

        $pelanggans = Pelanggan::whereHas('pemesanan') // hanya yang punya pemesanan
            ->withCount('pemesanan') // hitung jumlah pemesanan
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%$keyword%")
                        ->orWhere('username', 'like', "%$keyword%")
                        ->orWhere('email', 'like', "%$keyword%")
                        ->orWhere('nomor_telepon', 'like', "%$keyword%");
                });
            })
            ->paginate(10); // tampilkan 10 pelanggan per halaman

        return view('pelanggan.index', compact('pelanggans', 'keyword'));
    }
}
