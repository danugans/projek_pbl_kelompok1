<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarMejaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\InformationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RescheduleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TambahTransaksiController;

//Routing untuk Halaman Depan
Route::get('/', function () {
    return redirect()->route('index');
})->name('home');

Route::get('/event', [InformasiController::class, 'index'])->name('event');
Route::get('/event/{id}', [InformasiController::class, 'show'])->name('event.show');


//Admin
Route::get('/hendra', [AuthController::class, 'showLoginForm']);
Route::post('/hendra/submit', [AuthController::class, 'login'])->name('login.admin');

// Hanya bisa diakses jika sudah login (admin atau owner)

Route::middleware('auth.adminowner')->group(function () {
    Route::get('/beranda', [DashboardController::class, 'index'])->name('beranda');
    Route::get('/pelanggan', [PelangganAuthController::class, 'index'])->name('pelanggan.index');
    Route::get('/meja', [MejaController::class, 'index'])->name('meja.index');
    Route::post('/meja', [MejaController::class, 'store'])->name('meja.store');
    Route::get('/meja/{id}', [MejaController::class, 'show'])->name('meja.show');
    Route::put('/meja/{id}', [MejaController::class, 'update'])->name('meja.update');
    Route::delete('/meja/{id}', [MejaController::class, 'destroy'])->name('meja.destroy');

    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::post('/pemesanan/{id}/proses', [PemesananController::class, 'konfirmasi_pemesanan'])->name('pemesanan.proses');

    Route::get('/review', [ReviewController::class, 'index'])->name('review.index');

    Route::post('/pemesanan/{id}/kirim-pesan', [PemesananController::class, 'kirimPesan'])->name('pemesanan.kirimPesan');
    Route::resource('submit-event', InformationController::class);
    Route::post('/hendra/logout', [AuthController::class, 'logout'])->name('logout.admin');


    Route::get('/transaksi', [TambahTransaksiController::class, 'createTransaksi'])->name('admin.transaksi.create');
    Route::post('/transaksi/store', [TambahTransaksiController::class, 'storeTransaksi'])->name('admin.transaksi.store');
    Route::get('/transaksi/cek', [TambahTransaksiController::class, 'cekKetersediaanAdmin'])->name('admin.transaksi.cek');
});




// // Halaman ini hanya bisa diakses oleh owner
Route::middleware(['ownerOnly'])->group(function () {
    Route::get('/laporan-keuangan', [LaporanController::class, 'laporanKeuangan'])->name('dashboard.laporan.keuangan');
    Route::get('/laporan-keuangan/filter', [LaporanController::class, 'filterKeuangan'])->name('laporan-keuangan.filter');
    Route::get('/laporan-keuangan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan-keuangan.export.pdf');
});




//User 
Route::get('/daftarmeja', [DaftarMejaController::class, 'index'])->name('index');
Route::get('/daftarmeja/{id}', [DaftarMejaController::class, 'show'])->name('detailmeja.show');

Route::get('/register', [PelangganAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register/submit', [PelangganAuthController::class, 'register'])->name('register.submit');


Route::get('/login', [PelangganAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login/submit', [PelangganAuthController::class, 'login'])->name('login.submit');

Route::get('/lupa-password', [PelangganAuthController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/lupa-password', [PelangganAuthController::class, 'sendResetLink'])->name('password.send');

Route::get('/reset-password/{email}', [PelangganAuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PelangganAuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [PelangganAuthController::class, 'logout'])->name('logout');

    
Route::get('/cek-ketersediaan', [PemesananController::class, 'cekKetersediaan'])->name('pemesanan.cek');

Route::middleware('auth.pelanggan')->group(function () {
    Route::get('/pemesanan/rincian', [PemesananController::class, 'rincian'])->name('pemesanan.rincian');
    Route::post('/pemesanan/konfirmasi', [PemesananController::class, 'konfirmasi'])->name('pemesanan.konfirmasi');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran-gagal/{id}', [PemesananController::class, 'gagal'])->name('pembayaran.gagal');
    Route::get('/pembayaran/{id}/finish', [PembayaranController::class, 'finish'])->name('pembayaran.finish');
    Route::get('/pemesanan/succes/{id}', [PembayaranController::class, 'buktiPemesanan'])->name('pemesanan.succes');
    Route::get('/pemesanan/{id}/download', [PembayaranController::class, 'downloadBukti'])->name('pemesanan.download');
    Route::get('/pembayaran/batal/{id}', [PembayaranController::class, 'batal'])->name('pembayaran.batal');
    Route::get('/riwayat-pemesanan', [PemesananController::class, 'riwayat'])->name('riwayat.pemesanan');

    // Kalau pakai parameter {pemesanan_id}
    Route::get('/rating/{pemesanan_id}', [ReviewController::class, 'create'])
        ->name('rating.form');
    Route::post('/rating', [ReviewController::class, 'store'])->name('rating.store');
    Route::get('/pemesanan/{id}/reschedule', [RescheduleController::class, 'form'])
        ->name('pemesanan.reschedule.form');
    Route::post('/pemesanan/{id}/reschedule', [RescheduleController::class, 'submit'])
        ->name('pemesanan.reschedule.submit');
    Route::get('/pemesanan/cek', [RescheduleController::class, 'cek'])->name('reschedule.cek');
});
