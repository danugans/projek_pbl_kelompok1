@extends('layouts.app')

@section('content')

<!-- Laporan Kasir Start -->
<div class="container-fluid pt-4 px-4">
    <h5 style="margin-top: 20px;">Laporan Kasir</h5>
    <p style="color: rgb(250, 183, 0);">Laporan / Kasir</p>
    <div class="bg-white rounded p-4">
        <div class="row g-4">
            <div class="col-sm-6 col-md-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ubahPeriodeModal">
                    Ubah Periode
                </button>
                <button class="btn btn-success">Export PDF</button>
            </div>
        </div>
    </div>
</div>
<!-- Laporan Kasir End -->

<!-- Table Laporan Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <table class="table table-striped table-bordered table-responsive-md">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Penjualan</th>
                    <th scope="col">Pembelian</th>
                    <th scope="col">Pengeluaran</th>
                    <th scope="col">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data['tanggal'] }}</td>
                    <td>{{ $data['penjualan'] }}</td>
                    <td>{{ $data['pembelian'] }}</td>
                    <td>{{ $data['pengeluaran'] }}</td>
                    <td>{{ $data['pendapatan'] }}</td>
                </tr>
                @endforeach

                <tr>
                    <td colspan="5" class="text-end font-weight-bold">Total Pendapatan</td>
                    <td class="font-weight-bold">{{ $pendapatan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Table Laporan End -->

<!-- Modal Ubah Periode -->
<div class="modal fade" id="ubahPeriodeModal" tabindex="-1" aria-labelledby="ubahPeriodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ubahPeriodeModalLabel">Ubah Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('laporan.index') }}" method="GET">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

@endsection