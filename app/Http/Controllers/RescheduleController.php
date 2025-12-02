<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\PemesananSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RescheduleController extends Controller
{
    public function form($id)
    {
        $pemesanan = Pemesanan::with('slots', 'meja')
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->findOrFail($id);

        if ($pemesanan->id_pelanggan !== Auth::guard('pelanggan')->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        if ($pemesanan->reschedule_count >= 1) {
            return redirect()->route('riwayat.pemesanan')
                ->with('error', 'Reschedule sudah pernah diajukan untuk pemesanan ini.');
        }

        $earliest = $pemesanan->slots
            ->map(fn($slot) => Carbon::parse($pemesanan->tanggal . ' ' . $slot->jam_mulai))
            ->min();

        if (Carbon::now()->greaterThanOrEqualTo($earliest->copy()->subHour())) {
            return redirect()->route('riwayat.pemesanan')
                ->with('error', 'Reschedule tidak bisa diajukan karena sudah melewati batas waktu (H-1 jam).');
        }

        return view('landingpage.reschedule_form', compact('pemesanan'));
    }



    public function submit(Request $request, $id)
    {
        $pemesanan = Pemesanan::with('slots')
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->findOrFail($id);

        if ($pemesanan->id_pelanggan !== Auth::guard('pelanggan')->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        if ($pemesanan->reschedule_count >= 1) {
            return redirect()->back()->with('error', 'Reschedule sudah pernah diajukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'slots'   => 'required|array|min:1',
        ]);

        $newTanggal = $validated['tanggal'];
        $newSlots   = $validated['slots'];

        if (count($newSlots) !== $pemesanan->slots->count()) {
            return redirect()->back()->with('error', 'Jumlah slot harus sama dengan booking sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // === SIMPAN DATA LAMA UNTUK NOTIFIKASI ===
            $oldTanggal = $pemesanan->tanggal;

            // ✅ format hanya jam & menit
            $oldSlots = $pemesanan->slots->map(
                fn($s) =>
                Carbon::parse($s->jam_mulai)->format('H:i') . '-' .
                    Carbon::parse($s->jam_akhir)->format('H:i')
            )->toArray();

            // ---- validasi bentrok tetap sama ----
            foreach ($newSlots as $slot) {
                $exists = PemesananSlot::whereHas('pemesanan', function ($q) use ($newTanggal, $pemesanan) {
                    $q->where('tanggal', $newTanggal)
                        ->where('id_meja', $pemesanan->id_meja)
                        ->where('id', '!=', $pemesanan->id);
                })
                    ->where('jam_mulai', $slot['jam_mulai'])
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Slot {$slot['jam_mulai']} sudah dipesan orang lain. Silakan pilih jam lain.");
                }
            }

            // hapus slot lama
            PemesananSlot::where('pemesanan_id', $pemesanan->id)->delete();

            // update tanggal & count
            $pemesanan->update([
                'tanggal'          => $newTanggal,
                'reschedule_count' => $pemesanan->reschedule_count + 1,
            ]);

            // simpan slot baru
            foreach ($newSlots as $slot) {
                PemesananSlot::create([
                    'pemesanan_id' => $pemesanan->id,
                    'jam_mulai'    => $slot['jam_mulai'],
                    'jam_akhir'    => $slot['jam_akhir'],
                    'harga'        => $slot['harga'],
                ]);
            }

            DB::commit();

            // === KIRIM WHATSAPP KE ADMIN ===
            try {
                $adminNumber = '6282143556621'; // nomor admin

                $tglLama = Carbon::parse($oldTanggal)->translatedFormat('d F Y');
                $tglBaru = Carbon::parse($newTanggal)->translatedFormat('d F Y');

                // gabungkan slot lama & baru (lama sudah diformat H:i)
                $slotLama = implode(", ", $oldSlots);
                $slotBaru = implode(", ", array_map(
                    fn($s) => Carbon::parse($s['jam_mulai'])->format('H:i')
                        . '-' .
                        Carbon::parse($s['jam_akhir'])->format('H:i'),
                    $newSlots
                ));

                $text = "*Notifikasi Reschedule*\n\n"
                    . "Pelanggan : *{$pemesanan->pelanggan->nama}*\n\n"
                    . "📅 *Jadwal Lama*\nTanggal : {$tglLama}\nJam     : {$slotLama}\n\n"
                    . "📅 *Jadwal Baru*\nTanggal : {$tglBaru}\nJam     : {$slotBaru}\n\n"
                    . "Silakan dicek di sistem O'Bill.\n\n"
                    . "> Sent via fonnte.com";

                Http::withHeaders([
                    'Authorization' => env('FONNTE_TOKEN'),
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target'  => $adminNumber,
                    'message' => $text,
                ]);
            } catch (\Throwable $e) {
                Log::error('Gagal kirim WA admin: ' . $e->getMessage());
            }

            return redirect()->route('riwayat.pemesanan')
                ->with('success', 'Reschedule berhasil disimpan & notifikasi admin terkirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function cek(Request $request)
    {
        $tanggal  = $request->query('tanggal');
        $id_meja  = $request->query('id_meja');
        $exclude  = $request->query('exclude_id'); // agar slot lama tidak dianggap bentrok

        $bookedSlots = PemesananSlot::whereHas('pemesanan', function ($query) use ($tanggal, $id_meja, $exclude) {
            $query->where('tanggal', $tanggal)
                ->where('id_meja', $id_meja)
                ->when($exclude, fn($q) => $q->where('id', '!=', $exclude));
        })
            ->pluck('jam_mulai')
            ->toArray();

        return response()->json($bookedSlots);
    }
}
