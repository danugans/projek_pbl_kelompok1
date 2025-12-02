<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $fillable = ['id', 'tipe_meja', 'harga_sewa', 'deskripsi', 'foto', 'status'];

    public static function getReguler()
    {
        return self::where('tipe_meja', 'Reguler')
            ->where('status', 'aktif')
            ->get();
    }

    public static function getVIP()
    {
        return self::where('tipe_meja', 'VIP')
            ->where('status', 'aktif')
            ->get();
    }
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_meja');
    }
}
