<x-login>
    <section style="padding-top: 5cm; padding-bottom: 1cm;">
      <div class="container">
        <h3>Lupa Password</h3>
        <form method="POST" action="{{ route('password.send') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Masukkan Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            @error('email')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
      </div>
    </section>
  </x-login>
  