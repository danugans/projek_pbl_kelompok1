<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = Pengguna::where('jenis_pengguna', 'staff')->get();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nomer_telepon' => 'required',
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomer_telepon' => $request->nomer_telepon,
            'kode_referal' => $request->kode_referal,
            'jenis_pengguna' => 'staff', // Default sebagai staff
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Pengguna::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
