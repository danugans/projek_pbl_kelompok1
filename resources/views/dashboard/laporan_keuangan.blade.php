@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <h5 class="mb-4">Laporan Keuangan</h5>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 id="totalPendapatanText">Total Pendapatan: Rp 0</h6>
            <select id="filterSelect" class="form-select w-auto">
                <option value="tanggal">Tanggal</option>
                <option value="bulan">Bulan</option>
                <option value="tahun">Tahun</option>
            </select>
           
            <div id="filterInputs" class="d-flex gap-2 ms-2">
                <!-- Akan diisi dengan input filter -->
            </div>
            <a href="#" id="exportBtn" class="btn btn-danger btn-sm mb-3">Export ke PDF</a>
        </div>

        <canvas id="keuanganChart" height="100"></canvas>
    </div>
</div>

<!-- Tabel Transaksi -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <h5 class="mb-4">Detail Transaksi (Sudah Dibayar)</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody id="transaksiTableBody">
                    <tr>
                        <td colspan="5" class="text-center">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let keuanganChart;

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}

function fetchData(filterType, startDate, endDate = '') {
    let url = `/laporan-keuangan/filter?filter_type=${filterType}&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            document.getElementById('totalPendapatanText').textContent = 'Total Pendapatan: ' + formatRupiah(data.totalPendapatan);

            if (keuanganChart) keuanganChart.destroy();
            const ctx = document.getElementById('keuanganChart').getContext('2d');
            keuanganChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            const tbody = document.getElementById('transaksiTableBody');
            tbody.innerHTML = '';
            if (data.transaksi.length > 0) {
                data.transaksi.forEach(t => {
                    tbody.innerHTML += `<tr>
                        <td>${t.kode_booking}</td>
                        <td>${t.nama_pelanggan}</td>
                        <td>${t.tanggal}</td>
                        <td>${formatRupiah(t.total_bayar)}</td>
                    </tr>`;
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">Tidak ada data transaksi.</td></tr>`;
            }

            // Update link export PDF
            let pdfUrl = `/laporan-keuangan/export/pdf?filter_type=${filterType}&start_date=${startDate}`;
            if (endDate) pdfUrl += `&end_date=${endDate}`;
            document.getElementById('exportBtn').href = pdfUrl;
        })
        .catch(err => {
            console.error('Gagal memuat data:', err);
            alert('Terjadi kesalahan saat memuat laporan.');
        });
}

function updateFilterInputs() {
    const filterType = document.getElementById('filterSelect').value;
    const filterInputs = document.getElementById('filterInputs');
    filterInputs.innerHTML = '';

    if (filterType === 'tanggal') {
        const today = new Date().toISOString().split('T')[0];
        filterInputs.innerHTML = `
            <input type="date" id="startDate" class="form-control" value="${today}" required>
            <input type="date" id="endDate" class="form-control" value="${today}" required>
            <button onclick="applyFilter()" class="btn btn-primary btn-sm">Terapkan</button>`;
    } else if (filterType === 'bulan') {
    filterInputs.innerHTML = `
        <input type="month" id="startDate" class="form-control" required>
        <input type="month" id="endDate" class="form-control" required>
        <button onclick="applyFilter()" class="btn btn-primary btn-sm">Terapkan</button>`;
} else if (filterType === 'tahun') {
    filterInputs.innerHTML = `
        <input type="number" id="startDate" placeholder="Contoh: 2023" class="form-control" required>
        <input type="number" id="endDate" placeholder="Contoh: 2025" class="form-control" required>
        <button onclick="applyFilter()" class="btn btn-primary btn-sm">Terapkan</button>`;
}
}

function applyFilter() {
    const filterType = document.getElementById('filterSelect').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate') ? document.getElementById('endDate').value : '';

    if (!startDate || !endDate) {
        alert('Harap isi periode dengan benar.');
        return;
    }

    // Validasi jika tanggal awal lebih besar dari akhir
    if (filterType === 'tanggal' || filterType === 'bulan' || filterType === 'tahun') {
        const start = new Date(startDate);
        const end = new Date(endDate);
        if (start > end) {
            alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
            return;
        }
    }

    fetchData(filterType, startDate, endDate);
}


// Inisialisasi input saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    updateFilterInputs();
    // Secara otomatis menampilkan laporan hari ini
    const today = new Date().toISOString().split('T')[0];
    fetchData('tanggal', today, today);
});

document.getElementById('filterSelect').addEventListener('change', updateFilterInputs);
</script>
@endsection
