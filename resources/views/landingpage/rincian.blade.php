<x-layout>
    <div class="bg-light py-4">
        <div class="container">
            <div class="bg-white p-4 rounded shadow-sm">
                <h2 class="h4 fw-bold mb-4 text-center">🎱 Rincian Pemesanan</h2>
               

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded bg-light">
                            <h6 class="fw-bold mb-1"><i class="fas fa-calendar-alt me-2 text-primary"></i> Tanggal Booking</h6>
                            <p class="mb-0">{{ $tanggal }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded bg-light">
                            <h6 class="fw-bold mb-1"><i class="fas fa-table me-2 text-success"></i> Tipe Meja</h6>
                            <p class="mb-0">{{ $meja->tipe_meja }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-2"><i class="fas fa-clock me-2 text-warning"></i> Slot Waktu</h6>
                    <div class="row row-cols-1 row-cols-md-2 g-2">
                        @foreach ($slots as $slot)
                            <div class="col">
                                <div class="bg-body-secondary p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
                                    <div>{{ $slot['time'] }} - {{ \Carbon\Carbon::createFromFormat("H:i", $slot["time"])->addHour()->format("H:i") }}</div>
                                    <div class="text-danger fw-bold">Rp{{ number_format($slot['price'], 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <div class="border p-3 rounded bg-light d-flex justify-content-between align-items-center">
                        <span class="fw-bold">💰 Total Harga</span>
                        <span class="text-danger h5 mb-0">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>
                <form action="{{ route('pemesanan.konfirmasi') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_meja" value="{{ $meja->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    @foreach ($slots as $slot)
                        <input type="hidden" name="slots[]" value="{{ json_encode([
                            'jam_mulai' => $slot["time"],
                            'jam_akhir' => \Carbon\Carbon::createFromFormat("H:i", $slot["time"])->addHour()->format("H:i"),
                            'harga' => $slot["price"],
                        ]) }}">
                    @endforeach

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2"><i class="fas fa-wallet me-2 text-secondary"></i> Metode Pembayaran</h6>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="online" value="online" checked required>
                            <label class="form-check-label" for="online">Transfer Bank (Online)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="offline" value="offline" >
                            <label class="form-check-label" for="offline">Bayar di Tempat (Offline)</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="{{ route('detailmeja.show', ['id' => $meja->id]) }}" class="btn btn-outline-danger w-100 w-md-auto">
                            <i class="fas fa-times-circle me-1"></i> Batalkan
                        </a>
                        <button type="submit" class="btn btn-success w-100 w-md-auto" id="submitBtn">
                            <i class="fas fa-check-circle me-1"></i> Konfirmasi Pemesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const errorRedirect = @json(session('error_redirect'));

        @if ($errors->any() && session('error_redirect'))
            const errorMessages = {!! json_encode($errors->all()) !!};
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: errorMessages.map(msg => `<p>🔴 ${msg}</p>`).join(''),
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = errorRedirect;
            });
        @endif

        const box = document.getElementById('errorBox');
        if (box) {
            box.scrollIntoView({ behavior: 'smooth' });
        }

        document.querySelector('form').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
        });
    });
    </script>

</x-layout>
