<?php

namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Model implements AuthenticatableContract
{
    /** @use HasFactory<\Database\Factories\PenggunaFactory> */

    use HasApiTokens, HasFactory, Authenticatable;
    protected $table = 'penggunas';

    protected $fillable = ['username', 'email', 'password','jenis_pengguna'];
    protected $hidden = [
        'password',
    ];

    
}
