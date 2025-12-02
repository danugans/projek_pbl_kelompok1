<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

    protected $fillable = [
        'tanggal',
        'total_harga',
        'status_pembayaran',
        'id_pelanggan',
        'id_meja',
        'metode_pembayaran',
        'snap_token',
        'order_id',
        'proses_pemesanan',
        'reschedule_count'
    ];

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    // Relasi ke Meja
    public function meja()
    {
        return $this->belongsTo(Meja::class, 'id_meja');
    }
    public function slots()
{
    return $this->hasMany(PemesananSlot::class);
}

public function rating()
{
    return $this->hasOne(Review::class);
}

}
