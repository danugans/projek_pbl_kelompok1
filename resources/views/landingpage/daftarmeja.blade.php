<x-layout>
    <div class="container-fluid header bg-white">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4">
                    Main Billiard Lebih Seru dengan <span style="color: #a3c82c">Booking Online</span> di Osing Billiard Jajag!
                </h1>   
                <p class="animated fadeIn mb-4 pb-2">
                    Rasakan keseruan bermain billiard dengan fair play dan sportivitas tinggi, tanpa judi, hanya di Osing Billiard Jajag.
                </p>
                <a href="#booking" class="btn py-3 px-5 animated fadeIn" style="background-color: #a3c82c; color: white; border: none;">
                    Booking Sekarang
                </a>
                
            </div>
            <div class="col-md-6 animated fadeIn position-relative">
                <!-- Owl Carousel -->
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-wow-delay="0.1s">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="img/obil/1.jpg" class="d-block w-100" alt="Gambar 1">
                        </div>
                        <div class="carousel-item">
                            <img src="img/obil/2.jpg" class="d-block w-100" alt="Gambar 2">
                        </div>
                        <div class="carousel-item">
                            <img src="img/obil/3.jpg" class="d-block w-100" alt="Gambar 3">
                        </div>
                    </div>
                    <!-- Tombol Navigasi Bootstrap -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

            <!-- Search Start -->
            <div class="container-fluid mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px; background-color: #a3c82c;">
                <div class="container">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" class="form-control border-0 py-3" placeholder="Cari Meja">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select border-0 py-3">
                                        <option selected>Tipe Meja</option>
                                        <option value="1">Reguler</option>
                                        <option value="2">VIP</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select border-0 py-3">
                                        <option selected>Nomor Meja</option>
                                        <option value="1">Meja 1</option>
                                        <option value="2">Meja 2</option>
                                        <option value="3">Meja 3</option>
                                        <option value="3">Meja 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-dark border-0 w-100 py-3">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Search End -->
            <div id="booking">
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="section-header text-start mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                        <h1 class="display-5 mb-3">Daftar Meja Billiard</h1>
                        <p>Nikmati pengalaman bermain billiard tanpa antri! Sekarang kamu bisa booking meja billiard kapan saja dengan sistem reservasi yang praktis dan cepat.</p>
                    </div>
                </div>
                <div class="col-lg-6 text-start text-lg-end">
                    <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                        <li class="nav-item me-2">
                            <a class="btn btn-outline" style="border-color: #a3c82c; color: #a3c82c;" data-bs-toggle="pill" href="#tab-reguler" onclick="setActive(this)">Reguler</a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline" style="border-color: #a3c82c; color: #a3c82c;" data-bs-toggle="pill" href="#tab-vip" onclick="setActive(this)">VIP</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <!-- Meja Reguler -->
                <div id="tab-reguler" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        @foreach ($reguler as $meja)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <a href="{{ route('detailmeja.show', $meja->id) }}" class="text-decoration-none">
                                    <div class="product-item border rounded shadow-sm p-3 bg-white hover-scale">
                                        <div class="position-relative bg-light overflow-hidden rounded">
                                            <img class="img-fluid w-100" src="{{ asset('storage/' . $meja->foto) }}" alt="Meja Reguler">
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-2 py-1 px-3">
                                                <i class="fas fa-table"></i> {{ $meja->id }}
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <p class="text-muted fw-bold mb-1"></p>
                                            <h5 class="text-dark fw-bold">Osing Billiard Jajag</h5>
                                            <p class="text-muted mb-1">
                                                Rating:
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($avgRating))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif ($i - $avgRating < 1)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ $avgRating }})</span>
                                            </p>
                                            
                                            <div class="d-flex align-items-center text-muted">
                                                <p>Tipe Meja : {{ $meja->tipe_meja }}</p>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="text-muted">Mulai</span>
                                                <span class="fw-bold text-danger fs-5">Rp{{ number_format($meja->harga_sewa, 0, ',', '.') }} <span class="fs-6 text-muted">/ sesi</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            
                <!-- Meja VIP -->
                <div id="tab-vip" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        @foreach ($vip as $meja)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <a href="{{ route('detailmeja.show', $meja->id) }}" class="text-decoration-none">
                                    <div class="product-item border rounded shadow-sm p-3 bg-white hover-scale">
                                        <div class="position-relative bg-light overflow-hidden rounded">
                                            <img class="img-fluid w-100" src="{{ asset('storage/' . $meja->foto) }}" alt="Meja Reguler">
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-2 py-1 px-3">
                                                <i class="fas fa-table"></i> {{ $meja->id }}
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <p class="t`
                                            ext-muted fw-bold mb-1"></p>
                                            <h5 class="text-dark fw-bold">Osing Billiard Jajag</h5>
                                            <p class="text-muted mb-1">
                                                Rating:
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($avgRating))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif ($i - $avgRating < 1)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ $avgRating }})</span>
                                            </p>                                            
                                            <div class="align-items-center text-muted ">
                                                <p>Tipe Meja : {{ $meja->tipe_meja }}</p>
                                            </div>
                                            <div class="align-items-center text-muted">
                                                <p>Billiard profesional ✅</p>
                                            </div>
                                            <div class="align-items-center text-muted">
                                                <p>Ruangan Full AC ✅</p>
                                            </div>
                                            <div class="align-items-center text-muted">
                                                <p>Karaoke ✅</p>
                                            </div>
                                            <div class="justify-content-between align-items-center mt-3">
                                                <span class="text-muted">Mulai</span>
                                                <span class="fw-bold text-danger fs-5">Rp{{ number_format($meja->harga_sewa, 0, ',', '.') }} <span class="fs-6 text-muted">/ sesi</span></span>
                                            </div>
                                        </div>
                                    </div>
     
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            
                </div>
                    </div>
                </div>
                </div>
            </div>
    
</x-layout>