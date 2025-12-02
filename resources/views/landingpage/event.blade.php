<x-layout>
    <!-- HERO HEADER -->
    <div class="container-fluid py-5 mb-5" style="background: linear-gradient(to right, #a3c82c20, #ffffff);">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3 text-dark">Informasi & Event Terkini</h1>
            <p class="text-muted mb-0">Update terbaru seputar event turnament, promo, dan pengumuman dari Osing Billiard Jajag</p>
        </div>
    </div>

    <!-- LIST INFORMASI (Blog Style) -->
    <div id="list" class="container pb-5">
        <div class="row g-4">
            @foreach($informations as $info)
            <div class="col-lg-4 col-md-6">
                <div class="card blog-item h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="blog-img-wrapper">
                        <img src="{{ asset('storage/' . $info->image) }}" alt="{{ $info->title }}">
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-2">{{ $info->title }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="far fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($info->published_at)->format('d F Y') }}
                        </p>
                        <p class="text-muted mb-3">{{ Str::limit($info->content, 100) }}</p>
                        <a href="{{ route('event.show', $info->id) }}" class="read-more">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @push('styles')
    <style>
        /* Blog Card Style */
        .blog-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .blog-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
        }
        .blog-img-wrapper {
            position: relative;
            overflow: hidden;
            height: 200px;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        .blog-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            display: block;
        }
        .blog-item:hover .blog-img-wrapper img {
            transform: scale(1.1);
        }
        .card-body {
            padding: 1rem 1.25rem;
        }
        a.read-more {
            font-weight: 600;
            color: #198754; /* Bootstrap success color */
            text-decoration: none;
        }
        a.read-more:hover {
            text-decoration: underline;
        }
    </style>
    @endpush
</x-layout>
