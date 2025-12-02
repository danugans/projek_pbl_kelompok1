@extends('layouts.app')

@section('content')

<!-- Header -->
<div class="container-fluid pt-4 px-4">
    <h5 style="margin-top: 20px;">Daftar User</h5>
    <p style="color: rgb(250, 183, 0);">Database / User</p>
    <div class="bg-white rounded p-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                    + Tambah User
                </button>
            </div>
            <div class="col-sm-6 col-xl-4"></div>
            <div class="col-sm-6 col-xl-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari user" aria-label="Search">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table User -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Kode Referal</th>
                    <th>Jenis Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->nomer_telepon }}</td>
                    <td>{{ $user->kode_referal }}</td>
                    <td><span class="badge bg-success">{{ $user->jenis_pengguna }}</span></td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">✏️</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah User -->
        <div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama*</label>
                                    <input type="text" name="nama" id="nama" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email*</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password*</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nomer_telepon" class="form-label">Nomor Telepon*</label>
                                    <input type="text" name="nomer_telepon" id="nomer_telepon" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kode_referal" class="form-label">Kode Referal</label>
                                    <input type="text" name="kode_referal" id="kode_referal" class="form-control">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
