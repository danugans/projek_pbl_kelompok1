@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Meja Billiard</h4>
            <small class="text-muted">Database / Meja Billiard</small>
        </div>
        <form method="GET" action="{{ route('meja.index') }}" class="d-flex mt-2 mt-md-0">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Cari meja billiard">
            <button class="btn btn-warning" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMejaModal">
            Tambah Meja Billiard
        </button>
    </div>

    {{-- Tabel Data --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tipe Meja</th>
                        <th>Status meja</th>
                        <th>Harga Sewa (Rp)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($Mejas as $index => $meja)
                    <tr>
                        <td>{{ ($Mejas->currentPage() - 1) * $Mejas->perPage() + $loop->iteration }}</td>
                        <td>{{ $meja->tipe_meja }}</td>
                        <td>{{ $meja->status }}</td>
                        <td>{{ number_format($meja->harga_sewa, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ url('/meja/' . $meja->id) }}" class="btn btn-sm btn-primary">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data meja billiard.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan {{ $Mejas->firstItem() }} sampai {{ $Mejas->lastItem() }} dari total {{ $Mejas->total() }} data
                </small>
                {{ $Mejas->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Meja --}}
<div class="modal fade" id="tambahMejaModal" tabindex="-1" aria-labelledby="tambahMejaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('meja.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="tambahMejaModalLabel">Tambah Meja Billiard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    {{-- Foto --}}
                    <div class="col-md-4 text-center mb-3">
                        <img id="previewImage" src="https://via.placeholder.com/150" class="img-thumbnail mb-2" alt="Preview">
                        <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png,.webp" onchange="previewFile(this)">
                        <small class="text-muted">
                            *Opsional. Format diperbolehkan: JPG, JPEG, PNG, WEBP. Maksimal 3 MB*
                        </small>
                        @error('foto')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Form Fields --}}
                    <div class="col-md-8">

                        {{-- Nomor Meja --}}
                        <div class="mb-3">
                            <label for="id" class="form-label">Nomor Meja <span class="text-danger">*</span></label>
                            <input type="number" name="id" id="id" class="form-control" required placeholder="Contoh: 1, 2, 3 ...">
                            <small class="text-muted">
                                *Wajib diisi dan harus unik. Tidak boleh sama dengan nomor meja lainnya.*
                            </small>
                            @error('id')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Tipe Meja --}}
                        <div class="mb-3">
                            <label for="tipe_meja" class="form-label">Tipe Meja <span class="text-danger">*</span></label>
                            <select name="tipe_meja" id="tipe_meja" class="form-control" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Reguler">Reguler</option>
                                <option value="VIP">VIP</option>
                            </select>
                            <small class="text-muted">
                                *Wajib dipilih. Pilihan hanya Reguler atau VIP.*
                            </small>
                            @error('tipe_meja')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Harga Sewa --}}
                        <div class="mb-3">
                            <label for="harga_sewa" class="form-label">Harga Sewa (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_sewa" id="harga_sewa" class="form-control" min="0" required placeholder="Contoh: 15000">
                            <small class="text-muted">
                                *Wajib diisi. Masukkan angka tanpa titik. Tidak boleh minus.*
                            </small>
                            @error('harga_sewa')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control"></textarea>
                            <small class="text-muted">*Opsional. Isi jika ingin menambahkan informasi tambahan mengenai meja.*</small>
                            @error('deskripsi')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>

        </form>
    </div>
</div>


{{-- Script preview image --}}
<script>
    function previewFile(input) {
        let file = input.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection
