<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        $informations = Information::orderBy('published_at', 'desc')->get();
        return view('landingpage.event', compact('informations'));
    }

    public function show($id)
    {
        $info = Information::findOrFail($id);
        return view('landingpage.detailevent', compact('info'));
    }
}
