@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h5 class="mt-3">Daftar Pemesanan</h5>
    <p style="color: orange;">Database / Pemesanan</p>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded mb-3">
        <form method="GET" action="{{ route('pemesanan.index') }}">
            <div class="row g-2 justify-content-end">
                <div class="col-12 col-sm-8 col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Order ID..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-sm-auto">
                    <button class="btn btn-dark w-100">Cari</button>
                </div>
            </div>
        </form>        
    </div>

    <!-- Tabel di Desktop -->
    <div class="bg-white p-4 rounded d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-nowrap align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Meja</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Metode</th>
                        <th>Proses</th>
                        <th>Hubungi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemesanan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $item->meja->tipe_meja ?? '-' }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>
                            @foreach ($item->slots as $slot)
                                {{ $slot->jam_mulai }} - {{ $slot->jam_akhir }}<br>
                            @endforeach
                        </td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status_pembayaran === 'sudah_dibayar' ? 'success' : 'warning' }}">
                                {{ str_replace('_', ' ', ucfirst($item->status_pembayaran)) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                        <td>
                            @if($item->proses_pemesanan !== 'selesai')
                            <form id="form-konfirmasi-{{ $item->id }}" action="{{ route('pemesanan.proses', $item->id) }}" method="POST">
                                @csrf
                                <button type="button" class="btn btn-success btn-sm btn-konfirmasi" data-id="{{ $item->id }}">
                                    Konfirmasi
                                </button>
                            </form>
                            @else
                                <span class="badge bg-secondary">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('pemesanan.kirimPesan', $item->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-success btn-sm">
        <i class="bi bi-whatsapp"></i> Send Message
    </button>
</form>
                        </td>    
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data pemesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
            <small class="text-muted mb-2">
                Menampilkan {{ $pemesanan->firstItem() }} - {{ $pemesanan->lastItem() }} dari {{ $pemesanan->total() }} data
            </small>
            <div>
                {{ $pemesanan->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    <!-- Card View di Mobile -->
    <div class="d-block d-md-none">
        @forelse ($pemesanan as $item)
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title mb-1">{{ $item->pelanggan->nama ?? '-' }}</h6>
                <p class="mb-1"><strong>Meja:</strong> {{ $item->meja->tipe_meja ?? '-' }}</p>
                <p class="mb-1"><strong>Tanggal:</strong> {{ $item->tanggal }}</p>
                <p class="mb-1"><strong>Jam:</strong><br>
                    @foreach ($item->slots as $slot)
                        {{ $slot->jam_mulai }} - {{ $slot->jam_akhir }}<br>
                    @endforeach
                </p>
                <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                <p class="mb-1">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $item->status_pembayaran === 'sudah_dibayar' ? 'success' : 'warning' }}">
                        {{ str_replace('_', ' ', ucfirst($item->status_pembayaran)) }}
                    </span>
                </p>
                <p class="mb-2"><strong>Metode:</strong> {{ ucfirst($item->metode_pembayaran) }}</p>

                @if($item->proses_pemesanan !== 'selesai')
                <form id="form-konfirmasi-{{ $item->id }}" action="{{ route('pemesanan.proses', $item->id) }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-success btn-sm w-100 btn-konfirmasi" data-id="{{ $item->id }}">
                        Konfirmasi
                    </button>
                </form>
                @else
                    <span class="badge bg-secondary">Selesai</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada data pemesanan.</p>
        @endforelse

        <div class="d-flex justify-content-center mt-3">
            {{ $pemesanan->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-konfirmasi').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi Pemesanan?',
                text: "Pemesanan akan ditandai sebagai selesai dan sudah dibayar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, konfirmasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-konfirmasi-' + id).submit();
                }
            });
        });
    });
</script>
@endsection
