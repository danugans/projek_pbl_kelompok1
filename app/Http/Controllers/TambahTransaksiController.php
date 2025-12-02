<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\PemesananSlot;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TambahTransaksiController extends Controller
{
    public function createTransaksi()
    {
        $meja = Meja::all();
        return view('admin.transaksi.create', compact('meja'));
    }

    // SIMPAN TRANSAKSI
    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100|min:2',
            'nomor' => 'required|numeric|unique:pelanggan,nomor_telepon',
            'id_meja'        => 'required|exists:meja,id',
            'tanggal'        => 'required|date',
            'slots'          => 'required|json',
        ]);

        DB::beginTransaction();
        try {
            $uniqueSuffix = Str::random(5);
            $pelanggan = Pelanggan::firstOrCreate(
                [
                    'nama' => $request->input('nama_pelanggan'),
                    'nomor_telepon'  => $request->input('nomor')
                ],
                [
                    'email'          => 'guest_' . $uniqueSuffix . '@gmail.com',
                    'password'       => bcrypt('default123'),
                    'username'       => 'user_' . strtolower(Str::random(6)),
                ]
            );

            $slots = json_decode($request->input('slots'), true);
            if (!is_array($slots) || count($slots) === 0) {
                return back()->with('error', 'Slot waktu harus dipilih.');
            }

            // Cek apakah ada slot yang sudah dibooking orang lain
            foreach ($slots as $slot) {
                $exists = PemesananSlot::whereHas('pemesanan', function ($q) use ($request) {
                    $q->where('tanggal', $request->tanggal)
                        ->where('id_meja', $request->id_meja)
                        ->where('status_pembayaran', '!=', 'dibatalkan');
                })
                    ->where('jam_mulai', $slot['jam_mulai'])
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    return back()->with('error', "Slot {$slot['jam_mulai']} sudah dibooking pengguna lain!");
                }
            }

            $totalHarga = collect($slots)->sum('harga');

            $pemesanan = Pemesanan::create([
                'order_id'          => strtoupper(Str::random(6)),
                'tanggal'           => $request->input('tanggal'),
                'total_harga'       => $totalHarga,
                'status_pembayaran' => 'sudah_dibayar',
                'proses_pemesanan'  => 'selesai',
                'metode_pembayaran' => 'offline',
                'id_meja'           => $request->input('id_meja'),
                'id_pelanggan'      => $pelanggan->id,
            ]);

            foreach ($slots as $slot) {
                PemesananSlot::create([
                    'pemesanan_id' => $pemesanan->id,
                    'jam_mulai'    => $slot['jam_mulai'],
                    'jam_akhir'    => $slot['jam_akhir'],
                    'harga'        => $slot['harga'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.transaksi.create')->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan transaksi: ' . $e->getMessage());
        }
    }

    // CEK SLOT KOSONG UNTUK ADMIN (menampilkan slot yg sudah dipakai)
    public function cekKetersediaanAdmin(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $id_meja = $request->query('id_meja');

        $bookedSlots = PemesananSlot::whereHas('pemesanan', function ($query) use ($tanggal, $id_meja) {
            $query->where('tanggal', $tanggal)
                ->where('id_meja', $id_meja)
                ->where('status_pembayaran', '!=', 'dibatalkan');
        })->get(['jam_mulai', 'jam_akhir'])->toArray();

        return response()->json($bookedSlots);
    }
}
