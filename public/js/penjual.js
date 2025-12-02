document.addEventListener('DOMContentLoaded', function () {
    const cartItems = document.querySelector('#cart_items');
    let cart = [];
    let total = 0;

    // Cari Produk
    document.querySelector('#cari_produk').addEventListener('click', function () {
        const kode = document.querySelector('#kode_produk').value;
        fetch('/penjualan/cari-produk', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ kode })
        })
        .then(res => res.json())
        .then(data => {
            if (data) {
                cart.push({
                    id_produk: data.id,
                    kode: data.kode,
                    nama: data.nama,
                    harga_jual: data.harga_jual,
                    jumlah: 1,
                    diskon: 0,
                    subtotal: data.harga_jual
                });
                renderCart();
            } else {
                alert('Produk tidak ditemukan!');
            }
        });
    });

    // Render Cart
    function renderCart() {
        cartItems.innerHTML = '';
        total = 0;
        cart.forEach((item, index) => {
            total += item.subtotal;
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kode}</td>
                    <td>${item.nama}</td>
                    <td>Rp. ${item.harga_jual}</td>
                    <td>
                        <input type="number" min="1" value="${item.jumlah}" data-index="${index}" class="form-control update-jumlah">
                    </td>
                    <td>${item.diskon}%</td>
                    <td>Rp. ${item.subtotal}</td>
                    <td><button class="btn btn-danger btn-sm remove-item" data-index="${index}">Hapus</button></td>
                </tr>`;
            cartItems.innerHTML += row;
        });
        document.querySelector('#total').value = `Rp. ${total}`;
    }

    // Update Jumlah
    cartItems.addEventListener('change', function (e) {
        if (e.target.classList.contains('update-jumlah')) {
            const index = e.target.dataset.index;
            cart[index].jumlah = parseInt(e.target.value);
            cart[index].subtotal = cart[index].harga_jual * cart[index].jumlah;
            renderCart();
        }
    });

    // Hapus Item
    cartItems.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            const index = e.target.dataset.index;
            cart.splice(index, 1);
            renderCart();
        }
    });

    // Simpan Transaksi
    document.querySelector('#simpan_transaksi').addEventListener('click', function () {
        const pelanggan = document.querySelector('#kode_pelanggan').value;
        fetch('/penjualan/simpan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({
                id_pelanggan: pelanggan,
                total_item: cart.length,
                total_harga: total,
                diskon: document.querySelector('#diskon').value,
                bayar: document.querySelector('#bayar').value,
                diterima: document.querySelector('#diterima').value,
                cart
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            window.location.reload();
        });
    });
});
