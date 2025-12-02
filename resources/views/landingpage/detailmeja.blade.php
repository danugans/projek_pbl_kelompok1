<x-layout>
    <div class="bg-light">
        <div class="container py-2">
            @if (session('error'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session('error') }}'
        });
    </script>
@endif
            <div class="bg-white p-4 rounded shadow-sm">
                <div class="row g-4 mb-4 flex-column flex-md-row">
                    <div class="col-12 col-md-6">
                        <h2 class="h4 fw-bold">Meja Billiard {{ $meja->tipe_meja }}</h2>
                        <p class="text-muted">Osing Billiard Center akan disesuaikan dengan availability saat customer hadir di venue. Harga sewa sudah termasuk tax and service (nett).</p>
                        <p class="text-muted">{{ $meja->deskripsi ?? '-' }}</p>
                        <div class="d-flex gap-3 mb-3 flex-wrap">
                            <div><i class="fas fa-billiard"></i> Billiard</div>
                            <div><i class="fas fa-map-marker-alt"></i> Indoor</div>
                            <div><i class="fas fa-table"></i> Table Billiard</div>
                        </div>
                        <a href="{{ route('index') }}" class="btn btn-danger mb-3">{{ $meja->tipe_meja }}</a>
                        <a href="#cek" class="btn btn-warning mb-3">Cek ketersediaan</a>
                    </div>
                    <div class="col-12 col-md-6">
                        <img src="{{ asset('storage/' . $meja->foto) }}" class="img-fluid rounded w-100" style="height: 250px; object-fit: cover;" alt="Foto Meja">
                    </div>
                </div>                
            </div>
        </div>

        <!-- TANGGAL + KALENDER -->
        <div class="bg-light" id="cek">
            <div class="container py-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <div class="d-flex flex-wrap gap-2 flex-grow-1" id="date-buttons"></div>
                        <button class="btn btn-danger" id="calendar-btn"><i class="fas fa-calendar-alt"></i></button>
                    </div>                                       
                    <div id="calendar-container" class="bg-white p-3 rounded shadow-sm mb-3 d-none">
                        <input type="date" id="calendar-input" class="form-control">
                    </div>

                    <!-- SLOT WAKTU & HARGA -->
                    <button class="btn btn-warning mt-3 mb-3" id="toggle-availability">
                        Ketersediaan (<span id="available-count">0</span>)
                    </button>                    
                    <div class="bg-white p-4 rounded shadow-sm d-none" id="availability-section">
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3" id="time-slots"></div>
                        <div id="selected-slots" class="mt-3"></div>
                        <h5 class="fw-bold mt-3">Total Harga: <span id="total-price">Rp0</span></h5>
                        <button id="book-now" class="btn btn-danger mt-3 d-none">Pesan Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT SECTION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isLoggedIn = {{ Auth::guard('pelanggan')->check() ? 'true' : 'false' }};
            generateDates();

            const availabilityBtn = document.getElementById('toggle-availability');
            const availabilitySection = document.getElementById('availability-section');
            const calendarInput = document.getElementById('calendar-input');
            const calendarBtn = document.getElementById('calendar-btn');
            // === BATAS TANGGAL (Hari ini → 1 bulan ke depan) ===
let today = new Date();
let yyyy = today.getFullYear();
let mm = String(today.getMonth() + 1).padStart(2, '0');
let dd = String(today.getDate()).padStart(2, '0');

let minDate = `${yyyy}-${mm}-${dd}`;

// +1 bulan
let nextMonth = new Date();
nextMonth.setMonth(nextMonth.getMonth() + 1);

let yyyyMax = nextMonth.getFullYear();
let mmMax = String(nextMonth.getMonth() + 1).padStart(2, '0');
let ddMax = String(nextMonth.getDate()).padStart(2, '0');

let maxDate = `${yyyyMax}-${mmMax}-${ddMax}`;

calendarInput.setAttribute("min", minDate);
calendarInput.setAttribute("max", maxDate);

            const selectedSlotsContainer = document.getElementById('selected-slots');
            const totalPriceElement = document.getElementById('total-price');
            const bookNowButton = document.getElementById('book-now');

            let selectedDate = new Date().toISOString().split('T')[0];
            let selectedSlots = [];

            generateTimeSlots(selectedDate);

            availabilityBtn.addEventListener('click', () => {
                availabilitySection.classList.toggle('d-none');
            });

            calendarInput.addEventListener('change', function (e) {
                selectedDate = e.target.value;
                selectedSlots = [];
                updateSelectedSlots();
                generateTimeSlots(selectedDate);
            });

            calendarBtn.addEventListener('click', function () {
                document.getElementById('calendar-container').classList.toggle('d-none');
            });

            document.getElementById('time-slots').addEventListener('click', function (event) {
                if (event.target.closest('.schedule-btn')) {
                    let button = event.target.closest('.schedule-btn');
                    let time = button.dataset.time;
                    let price = parseInt(button.dataset.price);

                    if (button.classList.contains('btn-danger')) {
                        button.classList.remove('btn-danger');
                        button.classList.add('btn-light');
                        selectedSlots = selectedSlots.filter(slot => slot.time !== time);
                    } else {
                        button.classList.remove('btn-light');
                        button.classList.add('btn-danger');
                        selectedSlots.push({ time, price });
                    }

                    updateSelectedSlots();
                }
            });

            bookNowButton.addEventListener('click', function () {
    if (!isLoggedIn) {
        if (confirm('Anda harus login terlebih dahulu.')) {
            window.location.href = "{{ route('login') }}";
        }
        return;
    }

    const queryParams = new URLSearchParams({
        tanggal: selectedDate,
        id_meja: {{ $meja->id }},
        slots: JSON.stringify(selectedSlots)
    });

    window.location.href = `{{ route('pemesanan.rincian') }}?${queryParams.toString()}`;
});


            function generateDates() {
                const dateContainer = document.getElementById('date-buttons');
                dateContainer.innerHTML = '';
                const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                const today = new Date();

                for (let i = 0; i < 7; i++) {
                    let futureDate = new Date();
                    futureDate.setDate(today.getDate() + i);
                    let dayName = days[futureDate.getDay()];
                    let dateStr = futureDate.toISOString().split('T')[0];
                    let dateText = `${dayName} <br> ${futureDate.getDate()} ${futureDate.toLocaleString('id-ID', { month: 'short' })}`;
                    let btnClass = (i === 0) ? 'btn-danger fw-bold' : 'btn-light';

                    const button = document.createElement('button');
                    button.className = `btn ${btnClass}`;
                    button.dataset.date = dateStr;
                    button.innerHTML = dateText;

                    button.addEventListener('click', function () {
                        document.querySelectorAll('#date-buttons .btn').forEach(btn => {
                            btn.classList.remove('btn-danger', 'fw-bold');
                            btn.classList.add('btn-light');
                        });

                        this.classList.remove('btn-light');
                        this.classList.add('btn-danger', 'fw-bold');

                        selectedDate = this.dataset.date;
                        selectedSlots = [];
                        updateSelectedSlots();
                        generateTimeSlots(selectedDate);

                        document.getElementById('calendar-container').classList.add('d-none');
                    });

                    dateContainer.appendChild(button);
                }
            }

            function updateSelectedSlots() {
                selectedSlotsContainer.innerHTML = '';
                let total = 0;

                selectedSlots.forEach(slot => {
                    total += slot.price;
                    selectedSlotsContainer.innerHTML += `<p class="mb-1">Jam: ${slot.time} | Harga: Rp${slot.price.toLocaleString('id-ID')}</p>`;
                });

                totalPriceElement.textContent = `Rp${total.toLocaleString('id-ID')}`;
                bookNowButton.classList.toggle('d-none', selectedSlots.length === 0);
            }

            function nextHour(timeStr) {
                const [hours, minutes] = timeStr.split(':').map(Number);
                const next = new Date();
                next.setHours(hours + 1);
                next.setMinutes(minutes);
                return next.toTimeString().slice(0, 5);
            }

            function generateTimeSlots(selectedDate) {
                const timeContainer = document.getElementById('time-slots');
                timeContainer.innerHTML = '';
                const now = new Date();
                const selected = new Date(selectedDate);
                const mejaHarga = {{ $meja->harga_sewa }};
                let availableCount = 0;

                fetch(`{{ route('pemesanan.cek') }}?tanggal=${selectedDate}&id_meja={{ $meja->id }}&_=${Date.now()}`)
                    .then(res => res.json())
                    .then(bookedSlots => {
                        const timeSlots = [
                            "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00",
                            "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "00:00"
                        ];

                        timeSlots.forEach(startTime => {
                            const slotDate = new Date(`${selectedDate}T${startTime}:00`);
                            const isToday = selected.toDateString() === now.toDateString();
                            const isPast = isToday && slotDate < now;
                            const isBooked = bookedSlots.some(slot => slot.startsWith(startTime));

                            if (isPast) return;

                            const disabledClass = isBooked ? 'disabled btn-secondary' : 'btn-light';
                            const disabledAttr = isBooked ? 'disabled' : '';

                            timeContainer.innerHTML += `
                                <div class="col">
                                    <button class="btn ${disabledClass} p-2 w-100 text-center rounded schedule-btn"
                                            data-time="${startTime}" 
                                            data-price="${mejaHarga}" ${disabledAttr}>
                                        <p class="small mb-1">60 Menit</p>
                                        <p class="fw-bold mb-1">${startTime} - ${nextHour(startTime)}</p>
                                        <p class="mb-0">Rp. ${mejaHarga.toLocaleString('id-ID')}</p>
                                    </button>
                                </div>
                            `;
                            if (!isBooked) availableCount++;
                        });

                        document.getElementById('available-count').textContent = availableCount;
                    });
            }
        });

    </script>
</x-layout>
