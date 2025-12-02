@extends('layouts.app')

@section('content')
<style>
    .container-transaksi {
        max-width: 750px;
        margin: 0 auto;
        background: #ffffff;
        padding: 25px 35px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    #week-buttons button {
        flex: 1;
        min-width: 60px;
        text-align: center;
        border-radius: 10px;
        border: 1px solid #ddd;
        transition: all 0.2s ease;
    }

    #week-buttons button:hover {
        background-color: #f8f9fa;
        border-color: #bbb;
    }

    #week-buttons .active-day {
        background-color: #dc3545 !important;
        color: white;
        border-color: #dc3545;
        box-shadow: 0 0 8px rgba(220,53,69,0.4);
    }

    #slot-container button {
        min-width: 90px;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    #slot-container button.btn-success {
        background-color: #198754;
        border-color: #198754;
    }

    #slot-container button.btn-success:hover {
        background-color: #157347;
    }

    #total-harga {
        font-size: 1.1rem;
        color: #212529;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
        margin-top: 10px;
    }
</style>

<div class="container-transaksi mt-4">
    <h3 class="mb-4 text-center fw-bold text-primary">Tambah Transaksi Manual</h3>

    <form action="{{ route('admin.transaksi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
    <label class="form-label fw-semibold">
        Nama Pelanggan <span class="badge bg-danger">Wajib</span>
        <i class="bi bi-info-circle text-muted" title="Minimal 2 huruf, maksimal 100 karakter."></i>
    </label>
    <input 
        type="text" 
        name="nama_pelanggan" 
        class="form-control" 
        placeholder="Contoh: Guest / Andi"
        minlength="2"
        maxlength="100"
        required
        value="{{ old('nama_pelanggan') }}"
    >
    <small class="text-muted">Isi nama minimal 2 karakter.</small>

    @error('nama_pelanggan')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
</div>


        <div class="mb-3">
    <label class="form-label fw-semibold">
        Nomor WhatsApp <span class="badge bg-danger">Wajib</span>
        <span class="badge bg-warning text-dark">Unique</span>
        <i class="bi bi-info-circle text-muted" title="Hanya angka. Harus unik. Contoh: 08xxxxxxxx"></i>
    </label>

    <input 
        type="text" 
        name="nomor" 
        class="form-control" 
        placeholder="Contoh: 08XXXX" 
        required
        value="{{ old('nomor') }}"
    >
    <small class="text-muted">Nomor harus unik dan hanya berupa angka.</small>

    @error('nomor')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
</div>


       <div class="mb-3">
    <label class="form-label fw-semibold">
        Pilih Meja <span class="badge bg-danger">Wajib</span>
    </label>

    <select name="id_meja" id="id_meja" class="form-select" required>
        <option value="">-- Pilih Meja --</option>
        @foreach($meja as $m)
            <option value="{{ $m->id }}" data-harga="{{ $m->harga_sewa }}">
                {{ $m->tipe_meja }} (Rp{{ number_format($m->harga_sewa,0,',','.') }})
            </option>
        @endforeach
    </select>

    <small class="text-muted">Pastikan meja tersedia pada tanggal & jam yang dipilih.</small>
</div>


        <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal</label>
            <div class="d-flex gap-2 mb-2 flex-wrap" id="week-buttons"></div>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Slot Waktu</label>
            <div id="slot-container" class="d-flex flex-wrap gap-2"></div>
            <input type="hidden" name="slots" id="selected-slots">
            <div id="total-harga" class="fw-bold">Total: Rp0</div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success px-4 py-2">💾 Simpan Transaksi</button>
        </div>
    </form>
</div>

<script>
    const slotContainer = document.getElementById('slot-container');
    const selectedInput = document.getElementById('selected-slots');
    const totalHargaEl = document.getElementById('total-harga');
    let selected = [];
    let currentHarga = 0;

    const weekContainer = document.getElementById('week-buttons');
    const today = new Date();
    const hari = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    const bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

    for (let i = 0; i < 7; i++) {
        const date = new Date();
        date.setDate(today.getDate() + i);

        const btn = document.createElement('button');
        btn.type = "button";
        btn.className = "btn btn-light flex-fill";
        btn.innerHTML = `<strong>${hari[date.getDay()]}</strong><br>${date.getDate()} ${bulan[date.getMonth()]}`;

        btn.addEventListener('click', () => {
            document.querySelectorAll('#week-buttons button').forEach(b => b.classList.remove('active-day'));
            btn.classList.add('active-day');
            document.getElementById('tanggal').value = date.toISOString().split('T')[0];
            loadSlots();
        });

        if (i === 0) {
            btn.classList.add('active-day');
        }

        weekContainer.appendChild(btn);
    }

    // ============= BATAS MIN & MAX TANGGAL (Today → +7 Hari) =============
const tanggalInput = document.getElementById('tanggal');

// hari ini
let todayY = today.getFullYear();
let todayM = String(today.getMonth() + 1).padStart(2, '0');
let todayD = String(today.getDate()).padStart(2, '0');
let minDate = `${todayY}-${todayM}-${todayD}`;

// +7 hari
let maxDateObj = new Date();
maxDateObj.setDate(maxDateObj.getDate() + 7);

let maxY = maxDateObj.getFullYear();
let maxM = String(maxDateObj.getMonth() + 1).padStart(2, '0');
let maxD = String(maxDateObj.getDate()).padStart(2, '0');
let maxDate = `${maxY}-${maxM}-${maxD}`;

// pasang ke input date
tanggalInput.setAttribute("min", minDate);
tanggalInput.setAttribute("max", maxDate);

// default = hari ini
tanggalInput.value = minDate;


    document.getElementById('id_meja').addEventListener('change', function(){
        currentHarga = parseInt(this.selectedOptions[0]?.dataset.harga || 0);
        loadSlots();
    });

    function updateTotal() {
        const total = selected.reduce((sum, s) => sum + s.harga, 0);
        totalHargaEl.textContent = "Total: Rp" + total.toLocaleString('id-ID');
    }

    function loadSlots() {
        let tanggal = document.getElementById('tanggal').value;
        let id_meja = document.getElementById('id_meja').value;
        if (!tanggal || !id_meja) return;

        fetch(`{{ route('admin.transaksi.cek') }}?tanggal=${tanggal}&id_meja=${id_meja}`)
            .then(res => res.json())
            .then(booked => {
                slotContainer.innerHTML = '';
                selected = [];
                selectedInput.value = JSON.stringify(selected);
                updateTotal();

                const timeSlots = [
                    "09:00","10:00","11:00","12:00","13:00","14:00",
                    "15:00","16:00","17:00","18:00","19:00","20:00",
                    "21:00","22:00","23:00"
                ];

                const now = new Date();
                const todayStr = now.toISOString().split('T')[0];
                const currentHour = now.getHours();

                timeSlots.forEach(start => {
                    const hour = parseInt(start.split(':')[0]);
                    const end = (hour + 1) + ":00";

                    if (tanggal === todayStr && hour <= currentHour) return;

                    let isBooked = booked.some(s => s.jam_mulai === start);
                    let btn = document.createElement('button');
                    btn.type = "button";
                    btn.className = "btn btn-sm " + (isBooked ? "btn-secondary" : "btn-outline-primary");
                    btn.textContent = `${start} - ${end}`;
                    btn.disabled = isBooked;

                    btn.addEventListener('click', () => {
                        if (btn.classList.contains('btn-success')) {
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-primary');
                            selected = selected.filter(s => s.jam_mulai !== start);
                        } else {
                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-success');
                            selected.push({ jam_mulai: start, jam_akhir: end, harga: currentHarga });
                        }
                        selectedInput.value = JSON.stringify(selected);
                        updateTotal();
                    });

                    slotContainer.appendChild(btn);
                });
            });
    }

    loadSlots();
</script>
@endsection
