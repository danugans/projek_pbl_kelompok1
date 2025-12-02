<x-layout>
    <div class="container py-5" style="max-width: 650px;">
        <h3 class="mb-4 text-center fw-bold">Beri Rating untuk Pemesanan #{{ $pemesanan->order_id }}</h3>

        <div class="alert alert-info">
            <strong>Petunjuk Pengisian:</strong>
            <ul class="mb-0">
                <li><strong>Rating</strong> wajib dipilih (1 – 5 bintang).</li>
                <li><strong>Komentar</strong> bersifat opsional, namun dianjurkan untuk membantu peningkatan layanan.</li>
                <li>Pastikan data terisi dengan benar sebelum mengirim.</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('rating.store') }}">
            @csrf

            {{-- ID Pemesanan --}}
            <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">

            {{-- Rating --}}
            <div class="mb-3 text-center">
                <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                <p class="text-muted mb-2">Klik pada bintang untuk memilih rating Anda.</p>

                <div id="star-rating" class="mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star star @if(old('nilai_rating') >= $i) active @endif" data-value="{{ $i }}"></i>
                    @endfor
                </div>

                <input type="hidden" name="nilai_rating" id="rating-value" required value="{{ old('nilai_rating') }}">

                @error('nilai_rating')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- Komentar --}}
            <div class="mb-3">
                <label for="komentar" class="form-label fw-bold">Komentar (Opsional)</label>
                <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis komentar Anda...">{{ old('komentar') }}</textarea>

                <small class="text-muted">Komentar dapat membantu kami meningkatkan pelayanan.</small>

                @error('komentar')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                Kirim Rating
            </button>
        </form>
    </div>

    {{-- STYLE --}}
    <style>
        .star {
            font-size: 2.3rem;
            color: #ccc;
            cursor: pointer;
            transition: 0.2s;
        }
        .star:hover {
            transform: scale(1.1);
        }
        .star.active {
            color: gold;
        }
    </style>

    {{-- SCRIPT --}}
    <script>
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating-value');

        stars.forEach(star => {
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-value');
                ratingInput.value = rating;

                stars.forEach(s => {
                    s.classList.remove('active');
                    if (s.getAttribute('data-value') <= rating) {
                        s.classList.add('active');
                    }
                });
            });
        });
    </script>
</x-layout>
