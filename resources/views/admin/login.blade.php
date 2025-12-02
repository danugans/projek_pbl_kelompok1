<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Mencegah autofill di level browser -->
    <meta name="autocomplete" content="off">
    <meta name="autofill" content="off">
    <meta name="browsermode" content="disable-autofill">

    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login Admin</title>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-card {
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false,
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal!',
        html: `{!! implode('<br>', $errors->all()) !!}`
    });
</script>
@endif

<section class="py-5">
    <div class="container">
        <div class="login-card card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="text-center mb-4">Log in</h4>

                <!-- Form dengan autofill dinonaktifkan -->
                <form action="{{ route('login.admin') }}" method="POST" autocomplete="off">
                    @csrf

                    <!-- Dummy fields untuk mengecoh browser -->
                    <input type="text" style="display:none" autocomplete="username">
                    <input type="password" style="display:none" autocomplete="current-password">

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required autocomplete="off">
                            <option value="">Pilih Role</option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Email atau Username</label>
                        <input type="text" 
                               class="form-control" 
                               name="login" 
                               id="login" 
                               placeholder="Email atau Username" 
                               value="{{ old('login') }}" 
                               required 
                               autocomplete="off"
                               autocorrect="off"
                               autocapitalize="none"
                               spellcheck="false">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control" 
                               name="password" 
                               id="password" 
                               placeholder="Password" 
                               required 
                               autocomplete="new-password"
                               autocorrect="off"
                               autocapitalize="none"
                               spellcheck="false">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">Log in</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

</body>
</html>
