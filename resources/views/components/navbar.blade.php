<!-- Navbar Start -->
<div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
    <div class="top-bar row gx-0 align-items-center d-none d-lg-flex">
        <div class="col-lg-6 px-5 text-start">
            <small><i class="fa fa-map-marker-alt me-2"></i>Jl.Juanda No.120, Jajag, Kec.Gambiran</small>
            <small class="ms-4"><i class="fa fa-envelope me-2"></i>obcjajag@gmail.com</small>
        </div>
        <div class="col-lg-6 px-5 text-end">
            <small>Follow us:</small>
            <a class="text-body ms-3" href="#"><i class="fab fa-facebook-f"></i></a>
            <a class="text-body ms-3" href="#"><i class="fab fa-twitter"></i></a>
            <a class="text-body ms-3" href="#"><i class="fab fa-linkedin-in"></i></a>
            <a class="text-body ms-3" href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light py-lg-3 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="/" class="navbar-brand ms-4 ms-lg-0">
            <img src="/img/obil/obil1.png" alt="" width="200px">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0 align-items-center">
                <a href="/" class="nav-item nav-link">Daftar Meja</a>
                <a href="/event" class="nav-item nav-link">Event</a>
    
                {{-- Ikon Keranjang Booking --}}
                <div class="nav-item ms-lg-3 my-2 my-lg-0 position-relative">
                    <a href="{{ route('riwayat.pemesanan') }}" class="nav-link position-relative">
                        <i class="fas fa-shopping-cart fa-lg text-dark"></i>
                        @php
                            $jumlahPemesanan = 0;
                            if (Auth::guard('pelanggan')->check()) {
                                $jumlahPemesanan = \App\Models\Pemesanan::where('id_pelanggan', Auth::guard('pelanggan')->id())
                                    ->whereIn('status_pembayaran', ['sudah_dibayar', 'belum_dibayar'])
                                    ->count();
                            }
                        @endphp
                        @if($jumlahPemesanan > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $jumlahPemesanan }}
                            </span>
                        @endif
                    </a>
                </div>
    
                {{-- Ikon Profil --}}
                <div class="nav-item dropdown ms-lg-3 my-2 my-lg-0">
                    @if(Auth::guard('pelanggan')->check())
                        @php
                            $username = Auth::guard('pelanggan')->user()->username;
                            $initial = strtoupper(substr($username, 0, 1));
                        @endphp
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <strong>{{ $initial }}</strong>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end m-0 text-center">
                            <span class="dropdown-item-text fw-bold">{{ $username }}</span>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-2x text-dark"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end m-0">
                            <a href="{{ route('login') }}" class="dropdown-item">Masuk</a>
                            <a href="/register" class="dropdown-item">Daftar</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Navbar End -->
