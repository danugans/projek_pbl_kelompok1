@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h5 style="margin-top: 20px;">Data Review & Rating</h5>
    <p style="color: rgb(250, 183, 0);">Database / Review</p>
    <div class="bg-white rounded p-4">
        <table class="table table-striped table-bordered table-responsive-md">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Order ID</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $review->pemesanan->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $review->pemesanan->order_id ?? '-' }}</td>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($review->nilai_rating))
                                <i class="fas fa-star text-warning"></i>
                            @elseif ($i - $review->nilai_rating < 1)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </td>
                    <td>{{ $review->komentar }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada review.</td>
                </tr>
                @endforelse
                
                @if ($reviews->count() > 0)
                <tr>
                    <td colspan="5">
                        <strong>Rata-rata Rating:</strong> 
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                <i class="fas fa-star text-warning"></i>
                            @elseif ($i - $avgRating < 1)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <span class="ms-2">({{ $avgRating }})</span>
                    </td>
                </tr>
                @endif                
            </tbody>            
        </table>
    </div>
</div>
@endsection
