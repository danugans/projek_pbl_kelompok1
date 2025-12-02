<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Detail Supplier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            margin-top: 20px;
            color: #444;
        }
        .table-container {
            width: 80%;
            max-width: 700px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
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
        td:last-child {
            color: #222;
        }
        tr:hover {
            background-color: #e9f7ff;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #28a745;
            color: #fff;
        }
        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-kembali {
            background-color: #007BFF;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Detail Supplier</h1>
    <div class="table-container">
        <table>
            <tr>
                <th>Informasi</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Nama Supplier :</td>
                <td>{{ $supplier->nama_supplier }}</td>
            </tr>
            <tr>
                <td>Email Supplier :</td>
                <td>{{ $supplier->email_supplier }}</td>
            </tr>
            <tr>
                <td>Nomor Telepon :</td>
                <td>{{ $supplier->nomor_telepon_supplier }}</td>
            </tr>
            <tr>
                <td>Alamat Supplier :</td>
                <td>{{ $supplier->alamat_supplier }}</td>
            </tr>
            <tr>
                <td>Foto Supplier :</td>
                <td>
                    @if($supplier->foto_supplier)
                        <img src="{{ asset('storage/' . $supplier->foto_supplier) }}" alt="Foto Supplier" width="150">
                    @else
                        <em>Tidak ada foto</em>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="action-buttons">
        <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
        <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete">Hapus</button>
        </form>
        <a href="{{ route('supplier.index') }}" class="btn btn-kembali">Kembali</a>
    </div>

    <!-- Modal Edit Supplier -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_supplier" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="{{ $supplier->nama_supplier }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_supplier" class="form-label">Email Supplier</label>
                            <input type="email" class="form-control" id="email_supplier" name="email_supplier" value="{{ $supplier->email_supplier }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_telepon_supplier" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="nomor_telepon_supplier" name="nomor_telepon_supplier" value="{{ $supplier->nomor_telepon_supplier }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_supplier" class="form-label">Alamat Supplier</label>
                            <textarea class="form-control" id="alamat_supplier" name="alamat_supplier" rows="3" required>{{ $supplier->alamat_supplier }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="foto_supplier" class="form-label">Foto Supplier</label>
                            <input type="file" class="form-control" id="foto_supplier" name="foto_supplier" accept="image/*">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
