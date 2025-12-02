<x-login>
  <section class="bg-light" style="padding-top: 6cm; padding-bottom: 3cm;">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
          <h2 class="fs-1 mb-2 text-center">Daftar Akun</h2>
          <p class="fs-6 mb-2 text-center">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color: #0094A8; text-decoration: none;">Login di sini</a>
          </p>
          <hr class="w-50 mx-auto mb-5 mb-xl-9 border-dark-subtle">
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row gy-3 gy-md-4 gy-lg-0 align-items-xl-center">
        <div class="row justify-content-xl-center">
          <div class="col-12 col-xl-11">
            <div class="bg-white border rounded shadow-sm overflow-hidden">
              <form action="{{ route('register.submit') }}" method="POST" autocomplete="off">
                @csrf

                <div class="row gy-4 gy-xl-5 p-4 p-xl-5">

                  {{-- NAMA --}}
                  <div class="col-12 col-md-6">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap">
                    <small class="text-muted">Wajib diisi, minimal 2 karakter.</small>
                  </div>

                  {{-- USERNAME --}}
                  <div class="col-12 col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" value="{{ old('username') }}">
                    <small class="text-muted">Wajib, minimal 2 karakter, dan harus unik (belum digunakan).</small>
                  </div>

                  {{-- EMAIL --}}
                  <div class="col-12 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                      <span class="input-group-text">@</span>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}">
                    </div>
                    <small class="text-muted">Wajib diisi dan harus email yang belum terdaftar.</small>
                  </div>

                  {{-- NOMOR TELEPON --}}
                  <div class="col-12 col-md-6">
                    <label for="nomor_telepon" class="form-label">Nomor WhatsApp Aktif</label>
                    <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" placeholder="08xxxx" value="{{ old('nomor_telepon') }}">
                    <small class="text-muted">Wajib, hanya angka, dan harus unik (belum digunakan).</small>
                  </div>

                  {{-- PASSWORD --}}
                  <div class="col-12 col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password Minimal 6 Karakter">
                    <small class="text-muted">
                      Wajib diisi, minimal 6 karakter, mengandung:
                      <ul class="m-0 ps-3">
                        <li>Huruf besar (A-Z)</li>
                        <li>Karakter spesial (!@#$%^&*)</li>
                      </ul>
                    </small>
                  </div>

                  {{-- CONFIRM PASSWORD --}}
                  <div class="col-12 col-md-6">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password">
                    <small class="text-muted">Harus sama dengan password.</small>
                  </div>

                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn btn-lg" type="submit" style="background-color: #0094A8; color: aliceblue;">
                        Daftar Sekarang
                      </button>
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-login>
