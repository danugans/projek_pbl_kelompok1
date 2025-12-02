<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use Illuminate\Support\Facades\Storage;

class MejaController extends Controller
{
    public function index()
    {
        $Mejas = Meja::paginate(10); // 10 data per halaman
        return view('meja.index', compact('Mejas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|unique:meja,id',
            'tipe_meja' => 'required|in:Reguler,VIP',
            'harga_sewa' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('meja', 'public');
        }

        Meja::create([
            'id' => $request->id,
            'tipe_meja' => $request->tipe_meja,
            'harga_sewa' => $request->harga_sewa,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'status' => 'aktif'
        ]);

        return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan!');
    }


    public function show($id)
    {
        $meja = Meja::findOrFail($id);
        return view('meja.show', compact('meja'));
    }

    public function update(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);

        $request->validate([
            'tipe_meja' => 'required|in:Reguler,VIP',
            'harga_sewa' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        if ($request->hasFile('foto')) {
            if ($meja->foto) {
                Storage::disk('public')->delete($meja->foto);
            }
            $meja->foto = $request->file('foto')->store('meja', 'public');
        }

        $meja->update([
            'tipe_meja' => $request->tipe_meja,
            'harga_sewa' => $request->harga_sewa,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'foto' => $meja->foto

        ]);

        return redirect()->route('meja.index')->with('success', 'Meja berhasil diperbarui!');
    }
}
