@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h3>Pengaturan</h3>
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" name="nama" value="{{ $setting->nama ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Bidang Usaha</label>
            <input type="text" class="form-control" name="bidang_usaha" value="{{ $setting->bidang_usaha ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Negara</label>
            <input type="text" class="form-control" name="negara" value="{{ $setting->negara ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Provinsi</label>
            <input type="text" class="form-control" name="provinsi" value="{{ $setting->provinsi ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kota</label>
            <input type="text" class="form-control" name="kota" value="{{ $setting->kota ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" required>{{ $setting->alamat ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Sumber</label>
            <input type="text" class="form-control" name="sumber" value="{{ $setting->sumber ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Waktu Usaha</label>
            <input type="text" class="form-control" name="waktu_usaha" value="{{ $setting->waktu_usaha ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Toko</label>
            <input type="file" class="form-control" name="foto_toko">
            @if($setting->foto_toko)
                <img src="{{ asset('storage/' . $setting->foto_toko) }}" class="img-thumbnail mt-2" width="150">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
