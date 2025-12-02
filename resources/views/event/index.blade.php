@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Event & Informasi</h4>
            <small class="text-muted">Database / Event & Informasi</small>
        </div>
    </div>

    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahEventModal">
            Tambah Event
        </button>
    </div>

    {{-- Tabel Data --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($informations as $index => $info)
                    <tr>
                        <td>{{ ($informations->currentPage() - 1) * $informations->perPage() + $loop->iteration }}</td>
                        <td>{{ $info->title }}</td>
                        <td>{{ $info->created_at->format('d M Y') }}</td>
                        <td>
                            @if($info->image)
                                <img src="{{ asset('storage/' . $info->image) }}" alt="Event" width="80" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('submit-event.show', $info->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $info->id }}">Edit</button>
                            <form action="{{ route('submit-event.destroy', $info->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editEventModal{{ $info->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('submit-event.update', $info->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- JUDUL --}}
                    <div class="mb-3">
                        <label class="fw-semibold">Judul Event <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ $info->title }}" class="form-control" required>

                        <small class="text-muted">
                            Wajib diisi • Minimal 2 karakter • Maksimal 255 karakter
                        </small>

                        @error('title')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- KONTEN --}}
                    <div class="mb-3">
                        <label class="fw-semibold">Konten <span class="text-danger">*</span></label>
                        <textarea name="content" rows="4" class="form-control" required>{{ $info->content }}</textarea>

                        <small class="text-muted">
                            Wajib diisi • Berisi informasi event secara lengkap
                        </small>

                        @error('content')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- GAMBAR --}}
                    <div class="mb-3">
                        <label class="fw-semibold">Gambar</label><br>

                        @if($info->image)
                            <img src="{{ asset('storage/' . $info->image) }}" width="100" class="rounded mb-2">
                        @endif

                        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                        <small class="text-muted d-block">
                            Format: JPG, JPEG, PNG, WEBP • Max 3MB • Kosongkan jika tidak ingin mengganti
                        </small>

                        @error('image')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

            
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan {{ $informations->firstItem() }} sampai {{ $informations->lastItem() }} dari total {{ $informations->total() }} data
                </small>
                {{ $informations->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Event --}}
<div class="modal fade" id="tambahEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('submit-event.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- JUDUL --}}
                <div class="mb-3">
                    <label class="fw-semibold">Judul Event <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">

                    <small class="text-muted">
                        Wajib diisi • Minimal 2 karakter • Maksimal 255 karakter
                    </small>

                    @error('title')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- KONTEN --}}
                <div class="mb-3">
                    <label class="fw-semibold">Konten <span class="text-danger">*</span></label>
                    <textarea name="content" rows="4" class="form-control" required>{{ old('content') }}</textarea>

                    <small class="text-muted">
                        Wajib diisi • Berisi informasi lengkap tentang event
                    </small>

                    @error('content')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- GAMBAR --}}
                <div class="mb-3">
                    <label class="fw-semibold">Gambar (Opsional)</label>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted d-block">
                        Format: JPG, JPEG, PNG, WEBP • Max 3MB
                    </small>

                    @error('image')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>


@endsection
