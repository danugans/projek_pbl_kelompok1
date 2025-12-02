<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\PemesananSlot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiPemesanan extends Command
{
    protected $signature = 'notifikasi:kirim';
    protected $description = 'Kirim notifikasi WA ke pelanggan jika waktu pemesanan sudah tiba';

    public function handle()
    {
        $now = Carbon::now()->format('H:i');

        $slots = PemesananSlot::with('pemesanan.pelanggan')
            ->where('jam_mulai', $now)
            ->get();

        foreach ($slots as $slot) {
            $pelanggan = $slot->pemesanan->pelanggan;

            if ($pelanggan && $pelanggan->nomor_telepon) {
                $this->kirimWhatsapp($pelanggan->nomor_telepon, $pelanggan->nama, $slot->jam_mulai);
            }
        }

        return Command::SUCCESS;
    }

    private function kirimWhatsapp($nomor, $nama, $jam)
    {
        // format nomor sesuai gateway, misal: 628xxxx
        $nomor = preg_replace('/^0/', '62', $nomor);
        $pesan = "Halo *$nama*,\n\nPemesanan Anda untuk jam *$jam* telah dimulai. Silakan datang ke tempat untuk menyelesaikan transaksi. Terima kasih!";

        // --- Contoh dengan Wablas (atau ganti sesuai API gateway Anda) ---
        $response = Http::withHeaders([
            'Authorization' => 'YOUR_WABLAS_TOKEN',
        ])->post('https://kirim.pesan-wa.com/api/send-message', [
            'phone' => $nomor,
            'message' => $pesan,
        ]);

        // log jika gagal
        if (!$response->successful()) {
            Log::error("Gagal kirim WA ke $nomor: " . $response->body());
        }
    }
}
