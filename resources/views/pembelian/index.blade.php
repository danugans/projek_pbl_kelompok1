@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4>Transaksi Pembelian</h4>
    <div class="bg-white rounded p-4">

        <!-- Pilih Supplier -->
        <div class="form-group mb-3">
            <label for="supplier">Pilih Supplier</label>
            <select class="form-select" id="supplier">
                <option value="" disabled selected>Pilih Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Kode Produk -->
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Kode Produk" id="kode_produk">
            <button class="btn btn-primary" type="button" id="cari_produk" data-bs-toggle="modal" data-bs-target="#modalProduk">Cari</button>
        </div>

        <!-- Tabel Produk -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="cart_items">
                <!-- Data akan diisi secara dinamis dengan JavaScript -->
            </tbody>
        </table>

        <!-- Informasi Total -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h3 class="text-primary">Total Harga: <span id="total_harga" class="fw-bold">Rp. 0</span></h3>
            </div>
            <div class="col-md-6">
                <div class="form-group row mb-3">
                    <label for="kode_transaksi" class="col-sm-4 col-form-label">Kode Transaksi</label>
                    <div class="col-sm-8">
                        <input type="text" id="kode_transaksi" class="form-control">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="tanggal_transaksi" class="col-sm-4 col-form-label">Tanggal</label>
                    <div class="col-sm-8">
                        <input type="date" id="tanggal_transaksi" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="text-end mt-4">
            <button class="btn btn-success" id="simpan_transaksi">Simpan Transaksi</button>
        </div>
    </div>
</div>

<!-- Modal Produk -->
<div class="modal fade" id="modalProduk" tabindex="-1" aria-labelledby="modalProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProdukLabel">Pilih Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->kode }}</td>
                                <td>{{ $product->nama }}</td>
                                <td>Rp. {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="addToCart({{ json_encode($product) }})" data-bs-dismiss="modal">Tambah</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];

    function addToCart(product) {
        const existing = cart.find(item => item.id === product.id);

        if (existing) {
            existing.jumlah += 1;
        } else {
            cart.push({ 
                id_produk: product.id, 
                kode: product.kode, 
                nama: product.nama, 
                harga_beli: product.harga_beli, 
                jumlah: 1, 
                subtotal: product.harga_beli 
            });
        }

        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cart_items');
        tbody.innerHTML = '';

        let total = 0;
        cart.forEach((item, index) => {
            item.subtotal = item.jumlah * item.harga_beli;
            const tr = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kode}</td>
                    <td>${item.nama}</td>
                    <td>Rp. ${item.harga_beli}</td>
                    <td>
                        <input type="number" class="form-control" value="${item.jumlah}" onchange="updateQuantity(${index}, this.value)">
                    </td>
                    <td>Rp. ${item.subtotal}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += tr;
            total += item.subtotal;
        });

        document.getElementById('total_harga').innerText = `Rp. ${total}`;
    }

    function updateQuantity(index, value) {
        cart[index].jumlah = parseInt(value);
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    document.getElementById('simpan_transaksi').addEventListener('click', function () {
        const supplier = document.getElementById('supplier').value;
        const kodeTransaksi = document.getElementById('kode_transaksi').value;
        const tanggalTransaksi = document.getElementById('tanggal_transaksi').value;
        const totalHarga = cart.reduce((sum, item) => sum + item.subtotal, 0);

        if (!supplier || !kodeTransaksi || !tanggalTransaksi || cart.length === 0) {
            alert('Semua data harus diisi!');
            return;
        }

        fetch('{{ route('pembelian.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                supplier_id: supplier,
                kode_transaksi: kodeTransaksi,
                tanggal_transaksi: tanggalTransaksi,
                total_harga: totalHarga,
                cart_items: cart,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
