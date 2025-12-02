<x-layout>
    <div class="container py-5">
        <h2 class="text-center mb-4">🛒 Riwayat Pemesanan Anda</h2>

        @php
            $selesai = $pemesanan->filter(fn($p) => $p->proses_pemesanan === 'selesai');
            $belum = $pemesanan->filter(fn($p) => $p->proses_pemesanan !== 'selesai');
        @endphp

        {{-- Pemesanan belum selesai --}}
        @forelse ($belum as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">#{{ $item->order_id }}</h5>
                    <p class="card-text mb-1"><strong>Tanggal:</strong> {{ $item->tanggal }}</p>
                    <p class="card-text mb-1"><strong>Meja:</strong> {{ $item->meja->tipe_meja }}</p>
                    <p class="card-text mb-1"><strong>Total Harga:</strong> Rp{{ number_format($item->total_harga, 0, ',', '.') }}</p>
                    <p class="card-text mb-1"><strong>Status Pembayaran:</strong> 
                        <span class="badge {{ $item->status_pembayaran == 'sudah_dibayar' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($item->status_pembayaran) }}
                        </span>
                    </p>

                    @php
  
    $earliestSlot = null;
    foreach ($item->slots as $s) {
        $start = \Carbon\Carbon::parse($item->tanggal . ' ' . $s->jam_mulai);
        if (is_null($earliestSlot) || $start->lt($earliestSlot)) {
            $earliestSlot = $start;
        }
    }

    $canShowReschedule = ($item->reschedule_count < 1) && \Carbon\Carbon::now()->lt($earliestSlot->copy()->subHour());
@endphp

<div class="mt-3">
    <a href="{{ route('pemesanan.succes', ['id' => $item->id]) }}" class="btn btn-primary btn-sm">
        Lihat Bukti
    </a>

    @if($canShowReschedule)
        <a href="{{ route('pemesanan.reschedule.form', ['id' => $item->id]) }}" class="btn btn-warning btn-sm">
            Reschedule
        </a>
    @else
        @if($item->reschedule_count >= 1)
            <button class="btn btn-secondary btn-sm" disabled>Sudah Reschedule</button>
        @else
            <button class="btn btn-secondary btn-sm" disabled>Terlalu Dekat (H-1)</button>
        @endif
    @endif
</div>

                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">
                Anda belum memiliki pemesanan.
            </div>
        @endforelse

        {{-- Pemesanan selesai --}}
        @foreach ($selesai as $item)
            <div class="card mb-3 shadow-sm" style="background-color: rgba(8, 133, 74, 0.1);">
                <div class="card-body">
                    <h5 class="card-title">#{{ $item->order_id }}</h5>
                    <p class="card-text mb-1"><strong>Tanggal:</strong> {{ $item->tanggal }}</p>
                    <p class="card-text mb-1"><strong>Meja:</strong> {{ $item->meja->tipe_meja }}</p>
                    <p class="card-text mb-1"><strong>Total Harga:</strong> Rp{{ number_format($item->total_harga, 0, ',', '.') }}</p>
                    <p class="card-text mb-1"><strong>Status Pembayaran:</strong> 
                        <span class="badge {{ $item->status_pembayaran == 'sudah_dibayar' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($item->status_pembayaran) }}
                        </span>
                    </p>

                    <div class="mt-3">
                        <a href="{{ route('pemesanan.succes', ['id' => $item->id]) }}" class="btn btn-primary btn-sm">
                            Lihat Bukti
                        </a>     
                        @if($item->proses_pemesanan == 'selesai' && !$item->rating)
                        <a href="{{ route('rating.form', ['pemesanan_id' => $item->id]) }}" class="btn btn-success btn-sm">
                            Beri Rating
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
