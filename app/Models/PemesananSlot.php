<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananSlot extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_slots';

    protected $fillable = [
        'pemesanan_id',
        'jam_mulai',
        'jam_akhir',
        'harga',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

  
}

