<x-layout>
    <div class="bg-light py-5">
        <div class="container">
            <div id="buktiPemesanan" class="bg-white p-5 rounded shadow-sm">
                <div class="text-center mb-5">
                    <h2 class="h4 fw-bold text-success">✅ Bukti Pemesanan</h2>
                    <p class="text-muted">Berikut detail pemesanan Anda</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-light h-100">
                            <h6 class="fw-bold mb-2"><i class="fas fa-receipt me-2 text-primary"></i> ID Pemesanan</h6>
                            <p class="mb-0">{{ $pemesanan->order_id }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-light h-100">
                            <h6 class="fw-bold mb-2"><i class="fas fa-user me-2 text-info"></i> Nama Pelanggan</h6>
                            <p class="mb-0">{{ $pemesanan->pelanggan->nama }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-light h-100">
                            <h6 class="fw-bold mb-2"><i class="fas fa-calendar-alt me-2 text-success"></i> Tanggal Booking</h6>
                            <p class="mb-0">{{ $pemesanan->tanggal }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-light h-100">
                            <h6 class="fw-bold mb-2"><i class="fas fa-table me-2 text-warning"></i> Tipe Meja</h6>
                            <p class="mb-0">{{ $pemesanan->meja->tipe_meja }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-clock me-2 text-danger"></i> Slot Waktu</h6>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach ($pemesanan->slots as $slot)
                            <div class="col">
                                <div class="bg-body-secondary p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
                                    <div>{{ $slot->jam_mulai }} - {{ $slot->jam_akhir }}</div>
                                    <div class="text-danger fw-bold">Rp{{ number_format($slot->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-light">
                            <h6 class="fw-bold mb-2"><i class="fas fa-wallet me-2 text-secondary"></i> Status Pembayaran</h6>
                            <p class="mb-0">
                                @if ($pemesanan->status_pembayaran == 'sudah_dibayar')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif ($pemesanan->status_pembayaran == 'belum_dibayar')
                                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-light">
                            <h6 class="fw-bold mb-2"><i class="fas fa-money-bill-wave me-2 text-primary"></i> Total Harga</h6>
                            <p class="mb-0 text-danger fw-bold">Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i> Kembali ke Beranda
                    </a>
                </div>
                <div class="text-center mt-2">
                    <a href="{{ route('pemesanan.download', $pemesanan->id) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download me-2"></i> Download Bukti (PDF)
                    </a>
                </div>                
            </div>
        </div>
    </div>
</x-layout>
