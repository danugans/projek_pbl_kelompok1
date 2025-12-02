<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pelanggan extends Model implements AuthenticatableContract
{
    /** @use HasFactory<\Database\Factories\PelangganFactory> */

    use HasApiTokens, HasFactory, Authenticatable;
    protected $table = 'pelanggan';

    protected $fillable = ['nama', 'username', 'email', 'password', 'nomor_telepon'];
    protected $hidden = [
        'password',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pelanggan');
    }
}
