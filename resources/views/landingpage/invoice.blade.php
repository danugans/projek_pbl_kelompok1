<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $pemesanan->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
        }
        .container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
        }
        .header,
        .footer {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            font-size: 28px;
        }
        .info, .summary {
            margin-bottom: 20px;
        }
        .info p, .summary p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f8f8f8;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-warning {
            color: #ffc107;
        }
        .text-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Invoice Pemesanan</h2>
        <p>ID Pemesanan: <strong>{{ $pemesanan->order_id }}</strong></p>
        <p>Tanggal Invoice: {{ \Carbon\Carbon::parse($pemesanan->created_at)->format('d F Y') }}</p>
    </div>

    <div class="info">
        <p><strong>Nama Pelanggan:</strong> {{ $pemesanan->pelanggan->nama }}</p>
        <p><strong>No HP:</strong> {{ $pemesanan->pelanggan->nomor_telepon }}</p>
        <p><strong>Tanggal Booking:</strong> {{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d F Y') }}</p>
        <p><strong>Tipe Meja:</strong> {{ $pemesanan->meja->tipe_meja }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($pemesanan->slots as $slot)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $slot->jam_mulai }} - {{ $slot->jam_akhir }}</td>
                    <td class="text-end">Rp{{ number_format($slot->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total Harga:</strong> Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
        <p><strong>Status Pembayaran:</strong>
            @if ($pemesanan->status_pembayaran === 'sudah_dibayar')
                <span class="text-success">Lunas</span>
            @else
                <span class="text-warning">Menunggu Pembayaran</span>
            @endif
        </p>
    </div>

    <div class="footer">
        <p>Terima kasih telah melakukan pemesanan.</p>
        <p class="fw-bold">Billiard Center</p>
    </div>
</div>
</body>
</html>
