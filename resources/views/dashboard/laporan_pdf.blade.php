<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>
    <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>

    <h4>Detail Transaksi:</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $trx)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $trx['kode_booking'] }}</td>
                <td>{{ $trx['nama_pelanggan'] }}</td>
                <td>{{ $trx['tanggal'] }}</td>
                <td>Rp {{ number_format($trx['total_bayar'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5">Tidak ada data transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
