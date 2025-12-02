<x-layout>
    <div class="text-center mt-5">
        <h3>🔄 Mengarahkan ke Payment Gateway...</h3>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Midtrans --}}
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            snap.pay('{{ $pemesanan->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('pembayaran.finish', $pemesanan->id) }}";
                },
                onPending: function (result) {
                    Swal.fire({
                        title: 'Pembayaran Belum Selesai',
                        text: 'Apakah Anda ingin membatalkan pemesanan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan Pembayaran',
                        cancelButtonText: 'Batalkan Pemesanan',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // refresh page to show Snap again
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('pembayaran.batal', ['id' => $pemesanan->id]) }}";
                        }
                    });
                },
                onError: function (result) {
                    Swal.fire({
                        title: 'Pembayaran Belum Selesai',
                        text: 'Apakah Anda ingin membatalkan pemesanan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan Pembayaran',
                        cancelButtonText: 'Batalkan Pemesanan',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // refresh page to show Snap again
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('pembayaran.batal', ['id' => $pemesanan->id]) }}";
                        }
                    });
                },
                onClose: function () {
                    Swal.fire({
                        title: 'Pembayaran Belum Selesai',
                        text: 'Apakah Anda ingin membatalkan pemesanan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan Pembayaran',
                        cancelButtonText: 'Batalkan Pemesanan',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // refresh page to show Snap again
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = "{{ route('pembayaran.batal', ['id' => $pemesanan->id]) }}";
                        }
                    });
                }
            });
        });
    </script>
</x-layout>
