@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h4 class="fw-bold mb-0">Daftar Pelanggan</h4>
            <small class="text-muted">Database / Pelanggan</small>
        </div>
        <form method="GET" action="{{ route('pelanggan.index') }}" class="d-flex">
            <input type="text" name="search" value="{{ $keyword ?? '' }}" class="form-control me-2" placeholder="Cari pelanggan...">
            <button class="btn btn-warning" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    {{-- Tabel untuk desktop --}}
    <div class="card shadow-sm rounded d-none d-md-block">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Nomor Telepon</th>
                        <th>Total Dipesan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggans as $index => $pelanggan)
                    <tr>
                        <td>{{ ($pelanggans->currentPage() - 1) * $pelanggans->perPage() + $loop->iteration }}</td>
                        <td>{{ $pelanggan->nama }}</td>
                        <td>{{ $pelanggan->username }}</td>
                        <td>{{ $pelanggan->email }}</td>
                        <td>{{ $pelanggan->nomor_telepon }}</td>
                        <td><span class="badge bg-primary">{{ $pelanggan->pemesanan_count }}</span></td>                    
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data pelanggan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $pelanggans->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Card untuk mobile --}}
    <div class="d-block d-md-none">
        @forelse ($pelanggans as $pelanggan)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="card-title mb-1 fw-bold">{{ $pelanggan->nama }} <small class="text-muted">({{ $pelanggan->username }})</small></h6>
                <p class="mb-1"><strong>Email:</strong> {{ $pelanggan->email }}</p>
                <p class="mb-1"><strong>No. HP:</strong> {{ $pelanggan->nomor_telepon }}</p>
                <p class="mb-0"><strong>Total Dipesan:</strong> 
                    <span class="badge bg-primary">{{ $pelanggan->pemesanan_count }}</span>
                </p>
            </div>
        </div>
        @empty
        <p class="text-muted text-center">Tidak ada data pelanggan.</p>
        @endforelse

        {{-- Pagination mobile --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $pelanggans->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
