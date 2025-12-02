<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function laporanKeuangan()
    {
        return view('dashboard.laporan_keuangan');
    }

   public function filterKeuangan(Request $request)
{
    $filterType = $request->query('filter_type', 'tanggal');
    $start = $request->query('start_date');
    $end = $request->query('end_date');
    $now = Carbon::now();

    $query = Pemesanan::with(['pelanggan', 'meja'])
        ->where('status_pembayaran', 'sudah_dibayar');

    $labels = [];
    $data = [];
    $totalPendapatan = 0;
    $transaksiList = [];

    if ($filterType == 'tanggal') {
        if ($start) {
            $end = $end ?? $start; // Jika end kosong, gunakan start (real-time)
            $range = Carbon::parse($start)->daysUntil(Carbon::parse($end)->addDay());
            foreach ($range as $date) {
                $label = $date->format('Y-m-d');
                $labels[] = $label;
                $pendapatan = (clone $query)->whereDate('created_at', $label)->sum('total_harga');
                $data[] = $pendapatan;
                $totalPendapatan += $pendapatan;
            }
            $transaksiList = (clone $query)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->latest()->get();
        } else {
            return response()->json(['error' => 'Tanggal awal wajib diisi'], 400);
        }
    } elseif ($filterType == 'bulan') {
    if ($start && $end) {
        $startMonth = Carbon::createFromFormat('Y-m', $start);
        $endMonth = Carbon::createFromFormat('Y-m', $end);

        $range = [];
        for ($date = $startMonth->copy(); $date <= $endMonth; $date->addMonth()) {
            $range[] = $date->copy();
        }

        foreach ($range as $month) {
            $label = $month->format('F Y');
            $labels[] = $label;
            $pendapatan = (clone $query)->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_harga');
            $data[] = $pendapatan;
            $totalPendapatan += $pendapatan;
        }

        $transaksiList = (clone $query)->whereBetween('created_at', [$startMonth, $endMonth->endOfMonth()])->latest()->get();
    } else {
        return response()->json(['error' => 'Bulan awal dan akhir wajib diisi'], 400);
    }
}
elseif ($filterType == 'tahun') {
    if ($start && $end) {
        $startYear = Carbon::createFromFormat('Y', $start);
        $endYear = Carbon::createFromFormat('Y', $end);

        $range = range($startYear->year, $endYear->year);
        foreach ($range as $year) {
            $label = (string)$year;
            $labels[] = $label;
            $pendapatan = (clone $query)->whereYear('created_at', $year)->sum('total_harga');
            $data[] = $pendapatan;
            $totalPendapatan += $pendapatan;
        }

        $transaksiList = (clone $query)->whereBetween('created_at', [$startYear, $endYear->endOfYear()])->latest()->get();
    } else {
        return response()->json(['error' => 'Tahun awal dan akhir wajib diisi'], 400);
    }
}


    $transaksi = $transaksiList->map(function ($item) {
        return [
            'kode_booking' => $item->order_id ?? '-',
            'nama_pelanggan' => $item->pelanggan->nama ?? 'N/A',
            'tanggal' => $item->created_at->format('Y-m-d'),
            'total_bayar' => $item->total_harga,
        ];
    });

    return response()->json([
        'labels' => $labels,
        'data' => $data,
        'totalPendapatan' => $totalPendapatan,
        'transaksi' => $transaksi,
    ]);
}

    public function exportPdf(Request $request)
{
    $filter = $request->query('filter', 'harian');
    $now = Carbon::now();

    $query = Pemesanan::with(['pelanggan', 'meja'])
        ->where('status_pembayaran', 'sudah_dibayar');

    // Copy logika seperti di filterKeuangan()
    // (ringkas)
    $data = app()->call([$this, 'filterKeuangan'], ['request' => $request])->getData(true);

    $pdf = Pdf::loadView('dashboard.laporan_pdf', [
        'labels' => $data['labels'],
        'data' => $data['data'],
        'transaksi' => $data['transaksi'],
        'totalPendapatan' => $data['totalPendapatan'],
        'filter' => $filter
    ]);

    return $pdf->download('laporan-keuangan-' . now()->format('Ymd') . '.pdf');
}

}
