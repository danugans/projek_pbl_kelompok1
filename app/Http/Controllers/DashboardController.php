<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        // Total pelanggan yang pernah melakukan pemesanan
        $totalPelanggan = Pemesanan::distinct('id_pelanggan')->count('id_pelanggan');

        // Total pendapatan dari pemesanan yang sudah dibayar
        $totalPendapatan = Pemesanan::where('status_pembayaran', 'sudah_dibayar')->sum('total_harga');

        // Total booking
        $totalBooking = Pemesanan::count();

        // Data booking harian untuk grafik (7 hari terakhir)
        $laporanBooking = Pemesanan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('count(*) as jumlah')
        )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'asc')
            ->take(7)
            ->get();

        // Data pendapatan harian untuk grafik (7 hari terakhir)
        $laporanPendapatan = Pemesanan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('sum(total_harga) as total')
        )
            ->where('status_pembayaran', 'sudah_dibayar')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'asc')
            ->take(7)
            ->get();

        return view('dashboard.beranda', compact(
            'totalPelanggan',
            'totalPendapatan',
            'totalBooking',
            'laporanBooking',
            'laporanPendapatan'
        ));
    }
}
