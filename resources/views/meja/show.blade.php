<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detail Meja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .meja-image {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: #fff;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        td:first-child {
            font-weight: bold;
            color: #555;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #28a745;
            color: #fff;
            border: none;
        }
        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }
        .btn-kembali {
            background-color: #007BFF;
            color: #fff;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    @if($meja->foto)
        <img src="{{ asset('storage/' . $meja->foto) }}" alt="Foto Meja" class="meja-image">
    @else
        <img src="https://via.placeholder.com/600x300?text=No+Image" alt="Foto Meja" class="meja-image">
    @endif

    <table>
        <tr>
            <th>Informasi</th>
            <th>Detail</th>
        </tr>
        <tr>
            <td>Tipe Meja</td>
            <td>{{ $meja->tipe_meja }}</td>
        </tr>

        <tr>
            <td>Status meja</td>
            <td>{{ $meja->status}}</td>
        </tr>

        <tr>
            <td>Harga Sewa</td>
            <td>Rp {{ number_format($meja->harga_sewa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Deskripsi</td>
            <td>{{ $meja->deskripsi ?? '-' }}</td>
        </tr>
    </table>

    <div class="action-buttons">
        <a href="{{ route('meja.index') }}" class="btn btn-kembali">Kembali</a>
        <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
    </div>
</div>

<!-- Modal Edit Meja -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Meja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('meja.update', $meja->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tipe_meja" class="form-label">Tipe Meja</label>
                        <select class="form-control" id="tipe_meja" name="tipe_meja" required>
                            <option value="Reguler" {{ $meja->tipe_meja == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="VIP" {{ $meja->tipe_meja == 'VIP' ? 'selected' : '' }}>VIP</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status meja</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="aktif" {{ $meja->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $meja->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="harga_sewa" class="form-label">Harga Sewa</label>
                        <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" value="{{ $meja->harga_sewa }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi">{{ $meja->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Meja</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp">
                        @if($meja->foto)
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
