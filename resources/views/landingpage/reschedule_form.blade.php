<x-layout>
<div class="container py-5">
    <h3 class="mb-3">Reschedule Booking #{{ $pemesanan->order_id }}</h3>
    <p>Meja: {{ $pemesanan->meja->tipe_meja }}</p>
    <p>Jadwal lama: {{ $pemesanan->tanggal }}</p>

    <form action="{{ route('pemesanan.reschedule.submit', $pemesanan->id) }}" method="POST">
        @csrf

        {{-- ================= TANGGAL BARU ================= --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Pilih Tanggal Baru</label>

            {{-- 7 tombol cepat --}}
            <div class="d-flex flex-wrap gap-2 mb-2" id="date-buttons"></div>

            {{-- tombol buka kalender --}}
            <button type="button" class="btn btn-outline-secondary" id="calendar-btn">
                <i class="fas fa-calendar-alt"></i>
            </button>

            {{-- input datepicker --}}
            <div id="calendar-container" class="mt-2 d-none">
                <input type="date" id="calendar-input" class="form-control">
            </div>

            {{-- hidden input untuk form --}}
            <input type="hidden" id="tanggal-hidden" name="tanggal" required>
        </div>

        {{-- ================= SLOT BARU ================= --}}
        <div class="mb-4">
            <h5 class="fw-bold">Ganti Jam untuk Setiap Slot Lama</h5>
            @foreach($pemesanan->slots as $idx => $slot)
                <div class="border rounded p-3 mb-3">
                    <p class="mb-2">
                        Slot Lama:
                        <strong>{{ $slot->jam_mulai }} - {{ $slot->jam_akhir }}</strong>
                    </p>
                    <label class="form-label">Pilih Jam Baru</label>
                    <select class="form-select slot-select"
                            name="slots[{{ $idx }}][jam_mulai]"
                            data-idx="{{ $idx }}" required>
                        <option value="">-- Pilih Jam --</option>
                    </select>
                    <input type="hidden" name="slots[{{ $idx }}][jam_akhir]" class="jam-akhir-{{ $idx }}">
                    <input type="hidden" name="slots[{{ $idx }}][harga]" value="{{ $pemesanan->meja->harga_sewa }}">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Ajukan Reschedule</button>
        <a href="{{ route('riwayat.pemesanan') }}" class="btn btn-link">Batal</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dateButtons    = document.getElementById('date-buttons');
    const calBtn         = document.getElementById('calendar-btn');
    const calContainer   = document.getElementById('calendar-container');
    const calInput       = document.getElementById('calendar-input');
    const hiddenTanggal  = document.getElementById('tanggal-hidden');
    const slotSelects    = document.querySelectorAll('.slot-select');

    const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    const today = new Date();

    // === BATAS TANGGAL: Minimum hari ini, maksimum 7 hari ke depan ===
    let yyyy = today.getFullYear();
    let mm   = String(today.getMonth() + 1).padStart(2, '0');
    let dd   = String(today.getDate()).padStart(2, '0');
    let minDate = `${yyyy}-${mm}-${dd}`;

    let maxDay = new Date();
    maxDay.setDate(maxDay.getDate() + 7);
    let yyyyMax = maxDay.getFullYear();
    let mmMax   = String(maxDay.getMonth() + 1).padStart(2, '0');
    let ddMax   = String(maxDay.getDate()).padStart(2, '0');
    let maxDate = `${yyyyMax}-${mmMax}-${ddMax}`;

    calInput.setAttribute("min", minDate);
    calInput.setAttribute("max", maxDate);

    // ====== Buat 7 tombol tanggal ke depan ======
    for (let i = 0; i < 7; i++) {
        let d = new Date();
        d.setDate(today.getDate() + i);

        let btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `btn ${i === 0 ? 'btn-danger text-white fw-bold' : 'btn-light'}`;
        btn.innerHTML = `${days[d.getDay()]}<br>${d.getDate()} ${d.toLocaleString('id-ID',{ month:'short' })}`;
        btn.dataset.date = d.toISOString().split('T')[0];

        btn.addEventListener('click', function () {
            selectDate(this.dataset.date);

            document.querySelectorAll('#date-buttons .btn').forEach(b => {
                b.classList.remove('btn-danger','text-white','fw-bold');
                b.classList.add('btn-light');
            });

            this.classList.remove('btn-light');
            this.classList.add('btn-danger','text-white','fw-bold');
        });

        dateButtons.appendChild(btn);
        if (i === 0) selectDate(btn.dataset.date);
    }

    // Toggle datepicker
    calBtn.addEventListener('click', () => calContainer.classList.toggle('d-none'));

    // pilih tanggal dari calendar
    calInput.addEventListener('change', e => {
        selectDate(e.target.value);

        document.querySelectorAll('#date-buttons .btn').forEach(b => {
            b.classList.remove('btn-danger','text-white','fw-bold');
            b.classList.add('btn-light');
        });
    });

    function selectDate(dateStr) {
        hiddenTanggal.value = dateStr;
        updateSlotOptions(dateStr);
    }

    // ====== Load slot tersedia ======
    function updateSlotOptions(tanggal) {
        fetch(`{{ route('reschedule.cek') }}?tanggal=${tanggal}&id_meja={{ $pemesanan->id_meja }}&exclude_id={{ $pemesanan->id }}`)
            .then(r => r.json())
            .then(booked => {
                const timeOptions = [
                    "09:00","10:00","11:00","12:00","13:00","14:00",
                    "15:00","16:00","17:00","18:00","19:00","20:00",
                    "21:00","22:00","23:00"
                ];

                const now = new Date();
                const selected = new Date(tanggal + 'T00:00:00');

                slotSelects.forEach(sel => {
                    const idx = sel.dataset.idx;
                    sel.innerHTML = '<option value="">-- Pilih Jam --</option>';

                    timeOptions.forEach(start => {
                        const hourInt = parseInt(start.split(':')[0], 10);

                        const isPast =
                            (selected.toDateString() === now.toDateString()) &&
                            (hourInt <= now.getHours());

                        if (!isPast && !booked.includes(start)) {
                            const opt = document.createElement('option');
                            opt.value = start;
                            opt.textContent = `${start} - ${nextHour(start)}`;
                            sel.appendChild(opt);
                        }
                    });

                    sel.addEventListener('change', e => {
                        document.querySelector(`.jam-akhir-${idx}`).value =
                            nextHour(e.target.value);
                    });
                });
            });
    }

    function nextHour(t) {
        let [h, m] = t.split(':').map(Number);
        let d = new Date();
        d.setHours(h + 1, m);
        return d.toTimeString().slice(0, 5);
    }
});
</script>

</x-layout>
