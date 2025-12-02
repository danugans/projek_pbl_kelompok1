<x-login>
    <section style="padding-top: 5cm; padding-bottom: 1cm;">
      <div class="container">
        <h3>Reset Password</h3>
        <form method="POST" action="{{ route('password.update') }}">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">
          <div class="mb-3">
            <label for="password">Password Baru</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <div class="mb-3">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_confirmation" required>
          </div>
          <button type="submit" class="btn btn-success">Reset Password</button>
        </form>
      </div>
    </section>
  </x-login>
  