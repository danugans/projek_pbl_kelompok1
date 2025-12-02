<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// app/Http/Controllers/ReviewController.php
class ReviewController extends Controller
{
    public function create($pemesanan_id)
    {
        $pemesanan = Pemesanan::findOrFail($pemesanan_id);
        if ($pemesanan->id_pelanggan !== Auth::guard('pelanggan')->id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }
        if ($pemesanan->proses_pemesanan !== 'selesai' || $pemesanan->rating) {
            return redirect()->route('riwayat.pemesanan')->with('error', 'Pemesanan tidak valid atau sudah diberi rating.');
        }
        return view('review.create', compact('pemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'pemesanan_id' => 'required|exists:pemesanan,id',
                'nilai_rating' => 'required|integer|min:1|max:5',
                'komentar' => 'nullable|string'
            ],
            [
                'nilai_rating.required' => 'nilai rating wajib diisi.'
            ]
        );

        Review::create($request->only('pemesanan_id', 'nilai_rating', 'komentar'));

        return redirect()->route('riwayat.pemesanan')->with('success', 'Terima kasih atas rating Anda!');
    }

    //ADMIN
    public function index()
    {
        $reviews = Review::with('pemesanan.pelanggan')->latest()->get();
        $avgRating = round($reviews->avg('nilai_rating'), 2);

        return view('review.index', compact('reviews', 'avgRating'));
    }
}
