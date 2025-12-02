@extends('layouts.app')

@section('content')

<!-- Booking Billiard Dashboard Start -->
<div class="container-fluid pt-4 px-4">
    <h5 style="margin-top: 10px; margin-bottom: 20px;">Dashboard Booking Billiard</h5>
    <div class="row g-4">
        <div class="col-sm-6 col-xl-4">
            <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x" style="color: rgb(250, 183, 0);"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Pelanggan</p>
                    <h6 class="mb-0">{{ $totalPelanggan }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-money-bill fa-3x" style="color: rgb(250, 183, 0);"></i>
                <div class="ms-3">
                    <p class="mb-2">Pendapatan</p>
                    <h6 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-white rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-calendar-check fa-3x" style="color: rgb(250, 183, 0);"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Booking</p>
                    <h6 class="mb-0">{{ $totalBooking }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Booking Billiard Dashboard End -->

<!-- Grafik Booking Meja Billiard -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <h5 class="mb-4">Grafik Booking Meja (7 Hari Terakhir)</h5>
        <canvas id="bookingChart" height="100"></canvas>
    </div>
</div>

<!-- Grafik Pendapatan Harian -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <h5 class="mb-4">Grafik Pendapatan Harian (7 Hari Terakhir)</h5>
        <canvas id="pendapatanChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bookingData = @json($laporanBooking);
    const pendapatanData = @json($laporanPendapatan);

    const bookingChart = new Chart(document.getElementById('bookingChart'), {
        type: 'bar',
        data: {
            labels: bookingData.map(d => d.tanggal),
            datasets: [{
                label: 'Jumlah Booking',
                data: bookingData.map(d => d.jumlah),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const pendapatanChart = new Chart(document.getElementById('pendapatanChart'), {
        type: 'line',
        data: {
            labels: pendapatanData.map(d => d.tanggal),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: pendapatanData.map(d => d.total),
                backgroundColor: 'rgba(255, 206, 86, 0.5)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
