<x-layout>
    <div class="container py-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <!-- Gambar Utama -->
            <img src="{{ asset('storage/' . $info->image) }}" 
                 class="w-100" 
                 alt="{{ $info->title }}"
                 style="max-height: 400px; object-fit: cover;">

            <!-- Konten -->
            <div class="card-body p-4">
                <h2 class="fw-bold mb-3">{{ $info->title }}</h2>

                <p class="text-muted small mb-4">
                    <i class="far fa-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($info->published_at)->format('d F Y') }}
                </p>
                
                <div class="mb-4" style="line-height: 1.7; font-size: 1.05rem;">
                    {!! nl2br(e($info->content)) !!}
                </div>

                <a href="{{ route('event') }}" class="btn btn-outline-success rounded-pill px-4">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card-body p {
            font-size: 1rem;
            color: #333;
        }
        .card-body h2 {
            font-size: 1.75rem;
        }
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
    </style>
    @endpush
</x-layout>
