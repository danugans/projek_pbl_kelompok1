@extends('layouts.app')

@section('content')

<!-- Supplier Management Start -->
<div class="container-fluid pt-4 px-4">
    <h5 style="margin-top: 20px;">Supplier</h5>
    <p style="color: rgb(250, 183, 0);">Database / Supplier</p>
    <div class="bg-white rounded p-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSupplierModal">
                    Tambah Supplier
                </button>
            </div>
            <div class="col-sm-6 col-xl-4"></div>
            <div class="col-sm-6 col-xl-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari supplier" aria-label="Search">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Supplier Management End -->

<!-- Supplier List Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-white rounded p-4">
        <!-- Supplier Table -->
        <table class="table table-striped table-bordered table-responsive-md">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th scope="col">No</th>
                    <th scope="col">Nama Supplier</th>
                    <th scope="col">Email</th>
                    <th scope="col">Nomor Telepon</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                <tr>
                    <td><input type="checkbox"></td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $supplier->nama_supplier }}</td>
                    <td>{{ $supplier->email_supplier }}</td>
                    <td>{{ $supplier->nomor_telepon_supplier }}</td>
                    <td>{{ $supplier->alamat_supplier }}</td>
                    <td>
                        <a href="{{ route('supplier.detailSupplier', $supplier->id) }}" class="btn btn-primary">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Supplier -->
        <div class="modal fade" id="tambahSupplierModal" tabindex="-1" aria-labelledby="tambahSupplierModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahSupplierModalLabel">Tambah Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Nama Supplier -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_supplier" class="form-label">Nama Supplier*</label>
                                <input type="text" name="nama_supplier" id="nama_supplier" 
                                    class="form-control @error('nama_supplier') is-invalid @enderror" 
                                    placeholder="Masukkan nama supplier" required>
                                @error('nama_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Supplier -->
                            <div class="col-md-6 mb-3">
                                <label for="email_supplier" class="form-label">Email Supplier*</label>
                                <input type="email" name="email_supplier" id="email_supplier" 
                                    class="form-control @error('email_supplier') is-invalid @enderror" 
                                    placeholder="Masukkan email supplier" required>
                                @error('email_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="col-md-6 mb-3">
                                <label for="nomor_telepon_supplier" class="form-label">Nomor Telepon*</label>
                                <input type="text" name="nomor_telepon_supplier" id="nomor_telepon_supplier" 
                                    class="form-control @error('nomor_telepon_supplier') is-invalid @enderror" 
                                    placeholder="Masukkan nomor telepon" required>
                                @error('nomor_telepon_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat Supplier -->
                            <div class="col-md-12 mb-3">
                                <label for="alamat_supplier" class="form-label">Alamat Supplier*</label>
                                <textarea name="alamat_supplier" id="alamat_supplier" 
                                    class="form-control @error('alamat_supplier') is-invalid @enderror" 
                                    rows="3" placeholder="Masukkan alamat" required></textarea>
                                @error('alamat_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah Supplier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
            </ul>
        </nav>

        <p>Showing 1 to {{ $suppliers->count() }} of {{ $suppliers->total() }} entries</p>
    </div>
</div>
<!-- Supplier List End -->


@endsection
