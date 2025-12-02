<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use App\Models\Meja;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\PemesananSlot;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    public function rincian(Request $request)
    {
        $request->validate([
            'id_meja' => 'required|exists:meja,id',
            'tanggal' => 'required|date',
            'slots'   => 'required|string',
        ]);

        $slots = json_decode($request->slots, true);
        $totalHarga = collect($slots)->sum('price');
        $meja = Meja::find($request->id_meja);

        return view('landingpage.rincian', compact('slots', 'totalHarga', 'meja'))->with([
            'tanggal' => $request->tanggal,
        ]);
    }

    public function konfirmasi(Request $request)
    {
        $validated = $request->validate([
            'id_meja' => 'required|exists:meja,id',
            'tanggal' => 'required|date',
            'slots' => 'required|array',
            'metode_pembayaran' => 'required|in:offline,online',
        ]);

        $slots = array_map(fn($slot) => json_decode($slot, true), $validated['slots']);
        $tanggal = $validated['tanggal'];
        $id_meja = $validated['id_meja'];
        $metode = $validated['metode_pembayaran'];
        $id_pelanggan = Auth::guard('pelanggan')->id();

        // VALIDASI: offline hanya boleh satu slot
        if ($metode === 'offline' && count($slots) > 1) {
            return redirect()->back()
                ->withErrors(['Pembayaran di tempat hanya diperbolehkan untuk 1 slot (1 jam).'])
                ->with('error_redirect', route('detailmeja.show', ['id' => $id_meja]));
        }

        if ($metode === 'offline') {
            $sudahBookingOffline = DB::table('pemesanan')
                ->where('id_pelanggan', $id_pelanggan)
                ->where('metode_pembayaran', 'offline')
                ->where('status_pembayaran', 'belum_dibayar')
                ->exists();

            if ($sudahBookingOffline) {
                return redirect()->back()
                    ->withErrors(['Anda sudah memiliki pemesanan offline yang belum dikonfirmasi.'])
                    ->with('error_redirect', route('riwayat.pemesanan'));
            }
        }

        try {
            DB::beginTransaction();

            // Lock semua slot yang berkaitan (pencegahan race condition)
            foreach ($slots as $slot) {
                $bentrok = DB::table('pemesanan_slots')
                    ->join('pemesanan', 'pemesanan_slots.pemesanan_id', '=', 'pemesanan.id')
                    ->where('pemesanan.id_meja', $id_meja)
                    ->where('pemesanan.tanggal', $tanggal)
                    ->whereIn('pemesanan.status_pembayaran', ['belum_dibayar', 'sudah_dibayar'])
                    ->where(function ($query) use ($slot) {
                        $query->where('pemesanan_slots.jam_mulai', '<', $slot['jam_akhir']) // slot lama mulai sebelum baru berakhir
                            ->where('pemesanan_slots.jam_akhir', '>', $slot['jam_mulai']); // slot lama berakhir setelah baru mulai
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($bentrok) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(['Slot waktu sudah digunakan, silakan pilih waktu yang tersedia.'])
                        ->with('error_redirect', route('detailmeja.show', ['id' => $id_meja]));
                }
            }


            // Simpan Pemesanan
            $totalHarga = collect($slots)->sum('harga');

            $pemesanan = Pemesanan::create([
                'order_id' => strtolower(Str::random(5)),
                'tanggal' => $tanggal,
                'total_harga' => $totalHarga,
                'status_pembayaran' => 'belum_dibayar',
                'proses_pemesanan' => 'pending',
                'metode_pembayaran' => $metode,
                'id_pelanggan' => $id_pelanggan,
                'id_meja' => $id_meja,
            ]);

            foreach ($slots as $slot) {
                PemesananSlot::create([
                    'pemesanan_id' => $pemesanan->id,
                    'jam_mulai' => $slot['jam_mulai'],
                    'jam_akhir' => $slot['jam_akhir'],
                    'harga' => $slot['harga'],
                ]);
            }

            DB::commit();

            return redirect()->route('pembayaran.show', $pemesanan->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['Terjadi kesalahan saat memproses pemesanan. Silakan coba lagi.'])
                ->with('error_redirect', route('detailmeja.show', ['id' => $id_meja]));
        }
    }

    public function cekKetersediaan(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $id_meja = $request->query('id_meja');

        $bookedSlots = PemesananSlot::whereHas('pemesanan', function ($query) use ($tanggal, $id_meja) {
            $query->where('tanggal', $tanggal)
                ->where('id_meja', $id_meja);
        })->pluck('jam_mulai')->toArray();

        return response()->json($bookedSlots);
    }

    public function riwayat()
    {
        $pemesanan = Pemesanan::with(['slots', 'meja'])
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('landingpage.riwayat', compact('pemesanan'));
    }




    //UNTUK ADMIN
    public function index(Request $request)
    {
        $query = Pemesanan::with(['pelanggan', 'meja', 'slots']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('order_id', 'like', "%$search%");
        }

        $pemesanan = $query->latest()->paginate(10);

        return view('admin.pemesanan.index', compact('pemesanan'));
    }


    public function konfirmasi_pemesanan($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $pemesanan->proses_pemesanan = 'selesai';
        $pemesanan->status_pembayaran = 'sudah_dibayar';
        $pemesanan->save();

        return back()->with('success', 'Pemesanan berhasil dikonfirmasi.');
    }


    public function kirimPesan($id)
    {
        $pemesanan = Pemesanan::with(['pelanggan', 'meja', 'slots'])->findOrFail($id);
        $pelanggan = $pemesanan->pelanggan;

        // Ambil nomor pelanggan
        $nomor = preg_replace('/[^0-9]/', '', $pelanggan->nomor_telepon);
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        // Format tanggal
        $tanggal = \Carbon\Carbon::parse($pemesanan->tanggal)->translatedFormat('d F Y');

        // Gabungkan slot booking (bisa lebih dari 1)
        $slotText = "";
        foreach ($pemesanan->slots as $slot) {
            $slotText .= "- {$slot->jam_mulai} - {$slot->jam_akhir}\n";
        }

        // Susun pesan WhatsApp
        $text = "Halo {$pelanggan->nama},\n\n"
            . "Pengingat: booking Anda di *Osing Billiard Center Jajag* akan dimulai pada:\n\n"
            . "📅 Tanggal: {$tanggal}\n"
            . "🎱 Meja: {$pemesanan->meja->tipe_meja} (No. {$pemesanan->meja->id})\n\n"
            . "🕒 Jam Booking:\n{$slotText}\n"
            . "Pastikan datang tepat waktu untuk menikmati permainan 😊\n"
            . "Terima kasih sudah menggunakan layanan O'Bill 🎱";

        // Kirim via Fonnte
        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $text,
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Pesan berhasil dikirim ke ' . $pelanggan->nama);
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim pesan.');
        }
    }



    public function kirimPengingatOtomatis()
    {
        // Ambil semua pemesanan dengan status pending
        $pemesananList = Pemesanan::with(['pelanggan', 'meja', 'slots'])
            ->where('proses_pemesanan', 'pending') // <== filter pesanan pending
            ->get();

        foreach ($pemesananList as $pemesanan) {
            $pelanggan = $pemesanan->pelanggan;

            // Normalisasi nomor
            $nomor = preg_replace('/[^0-9]/', '', $pelanggan->nomor_telepon);
            if (substr($nomor, 0, 1) === '0') {
                $nomor = '62' . substr($nomor, 1);
            }

            // Format tanggal booking
            $tanggal = Carbon::parse($pemesanan->tanggal)->translatedFormat('d F Y');

            // Gabungkan slot booking (bisa lebih dari 1)
            $slotText = "";
            foreach ($pemesanan->slots as $s) {
                $slotText .= "- {$s->jam_mulai} - {$s->jam_akhir}\n";
            }

            // Susun pesan
            $text = "Halo {$pelanggan->nama},\n\n"
                . "Pengingat: booking Anda di *Osing Billiard Center Jajag* "
                . "masih berstatus *Pending*.\n\n"
                . "📅 Tanggal: {$tanggal}\n"
                . "🎱 Meja: {$pemesanan->meja->tipe_meja} (No. {$pemesanan->meja->id})\n\n"
                . "🕒 Jam Booking:\n{$slotText}\n"
                . "Silakan segera melakukan pembayaran untuk mengamankan slot Anda. "
                . "Terima kasih sudah menggunakan layanan O'Bill 🎱";

            // Kirim via Fonnte
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target'  => $nomor,
                'message' => $text,
            ]);

            // Log hasil pengiriman
            Log::info('Pengingat PENDING terkirim ke ' . $nomor . ' | Response: ' . $response->body());
        }
    }
}
