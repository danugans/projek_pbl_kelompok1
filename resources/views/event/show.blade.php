@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="fw-bold">{{ $information->title }}</h3>
            <p class="text-muted">Diposting pada {{ $information->created_at->format('d M Y') }}</p>

            @if($information->image)
                <img src="{{ asset('storage/' . $information->image) }}" alt="Event" class="img-fluid mb-3 rounded">
            @endif

            <p>{{ $information->content }}</p>

            <a href="{{ route('submit-event.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
