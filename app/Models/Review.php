<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';
    protected $fillable = ['pemesanan_id', 'nilai_rating', 'komentar'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
