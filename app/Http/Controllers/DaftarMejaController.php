<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use Illuminate\Support\Facades\Storage;
use App\Models\Review;

class DaftarMejaController extends Controller
{
    public function index()
    {
        $reguler = Meja::getReguler();
        $vip = Meja::getVIP();
        $avgRating = round(Review::avg('nilai_rating'), 2);

        return view('landingpage.daftarmeja', compact('reguler', 'vip', 'avgRating'));
    }

    public function show($id)
    {
        $meja = Meja::findOrFail($id);
        return view('landingpage.detailmeja', compact('meja'));
    }
}
