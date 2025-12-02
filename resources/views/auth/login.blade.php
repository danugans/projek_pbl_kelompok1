<x-login>
  <section class="bg-light" style="padding-top: 5cm; padding-bottom: 1cm;">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
          <h2 class="fs-1 mb-2 text-center">Login</h2>
          <p class="fs-6 mb-2 text-center">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color: #0094A8; text-decoration: none;">Daftar di sini</a>
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

              <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Anti auto-fill -->
                <input type="text" style="display:none" autocomplete="username">
                <input type="password" style="display:none" autocomplete="current-password">

                <div class="row gy-4 gy-xl-5 p-4 p-xl-5">
                  
                  {{-- USERNAME/EMAIL --}}
                  <div class="col-12">
                    <label for="username" class="form-label">Username atau Email</label>
                    <input type="text"
                           class="form-control"
                           id="username"
                           name="username"
                           placeholder="Masukkan username atau email"
                           value="{{ old('username') }}"
                           autocomplete="off"
                           autocapitalize="none"
                           autocorrect="off"
                           spellcheck="false">
                    <small class="text-muted">Wajib diisi. Bisa menggunakan username atau email yang terdaftar.</small>
                  </div>

                  {{-- PASSWORD --}}
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="Masukkan Password"
                           autocomplete="new-password"
                           autocorrect="off"
                           autocapitalize="none"
                           spellcheck="false">
                    <small class="text-muted">Wajib diisi. Minimal 6 karakter.</small>
                  </div>

                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn btn-lg"
                        type="submit"
                        style="background-color: #0094A8; color: aliceblue;">
                        Masuk
                      </button>
                    </div>
                  </div>

                  <a href="{{ route('password.forgot') }}"
                     style="color: #0094A8; text-decoration: none; text-align: end;">
                    Lupa Password?
                  </a>

                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-login>
