@extends('layouts.template')

@section('title', 'Ulasan Toko | LelangGame')

@section('content')
<a href="#" id="btnScrollTop" class="btn btn-primary position-fixed rounded-5 btn-lg fw-bold bottom-0 end-0 mb-5 me-3 fs-3" style="z-index: 9999; display: none;"><i class="bi bi-arrow-up"></i></a>
<div class="container">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ulasan Toko</li>
        </ol>
    </nav>
    <h2 class="fw-semibold">Ulasan Toko</h2>
    <hr>
    <div id="alert-container"></div>

    <div class="row mb-5">
        <div class="col-md-12">
            <h4 class="fw-semibold">Statistik Ulasan</h4>
            <div class="card">
                    <div class="card-body">
                        @if ($totalReviews > 0)
                            @for ($i = 5; $i >= 1; $i--)
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    @for ($j = 1; $j <= 5; $j++)
                                        @if ($j <= $i)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor

                                    <div class="progress flex-grow-1 mx-2" role="progressbar"
                                        aria-label="Rating {{ $i }} stars"
                                        aria-valuenow="{{ $ratingPercentages[$i] }}" aria-valuemin="0" aria-valuemax="100"
                                        style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $ratingPercentages[$i] }}%">
                                        </div>
                                    </div>

                                    <span class="text-secondary"
                                        style="min-width: 50px;">{{ number_format($ratingStats[$i]) }}</span>
                                </div>
                            @endfor
                            <hr>
                            <p class="m-0 text-secondary">Total Ulasan ({{ $totalReviews }})</p>
                            <p class="m-0 text-secondary">Rating Toko <i class="bi bi-star-fill text-warning"></i> {{ number_format($averageRating ?? 0, 1) }} / 5.0</p>
                        @else
                            <div class="text-center text-secondary">
                                <i class="bi bi-star" style="font-size: 48px;"></i>
                                <p class="mb-0 mt-2">Belum Ada Ulasan</p>
                            </div>
                        @endif
                    </div>
                </div>
        </div>
    </div>

    {{-- <div class="row mb-4">
        <div class="col-md-12">
            <h5>Distribusi Rating:</h5>
            @for($i = 5; $i >= 1; $i--)
                <div class="mb-2">
                    <span class="me-2">
                        @for($j = 1; $j <= $i; $j++)
                            ⭐
                        @endfor
                    </span>
                    <span class="badge bg-primary">{{ $ratingDistribution[$i] }} ulasan</span>
                </div>
            @endfor
        </div>
    </div> --}}

    {{-- <hr> --}}
    <form id="filter-form">
        <div class="row">
            <h4 class="fw-semibold">Filter Produk</h4>
            <div class="col-md-6 mt-3">
                <select name="product_id" id="product_id" class="form-select">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_id }}" {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mt-3 text-end">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="rating" id="rating-all" value="" {{ !request('rating') ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-all">Semua</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-5" value="5" {{ request('rating') == '5' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-5">
                        <i class="bi bi-star-fill text-warning"></i> 5
                    </label>

                    <input type="radio" class="btn-check" name="rating" id="rating-4" value="4" {{ request('rating') == '4' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-4">
                        <i class="bi bi-star-fill text-warning"></i> 4
                    </label>

                    <input type="radio" class="btn-check" name="rating" id="rating-3" value="3" {{ request('rating') == '3' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-3">
                        <i class="bi bi-star-fill text-warning"></i>  3
                    </label>

                    <input type="radio" class="btn-check" name="rating" id="rating-2" value="2" {{ request('rating') == '2' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-2">
                        <i class="bi bi-star-fill text-warning"></i> 2
                    </label>

                    <input type="radio" class="btn-check" name="rating" id="rating-1" value="1" {{ request('rating') == '1' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-1">
                        <i class="bi bi-star-fill text-warning"></i> 1
                    </label>
                </div>
            </div>
        </div>
    </form>
    <hr>
    <div id="reviews-container">
        @include('partials.reviews_table', ['comments' => $comments])
    </div>

    <div id="pagination-container" class="mt-3">
        {{ $comments->links() }}
    </div>
</div>

<script>
    document.getElementById('product_id').addEventListener('change', function() {
        filterReviews();
    });

    document.querySelectorAll('input[name="rating"]').forEach(radio => {
        radio.addEventListener('change', function() {
            filterReviews();
        });
    });

    function filterReviews(page = 1) {
        const product_id = document.getElementById('product_id').value;
        const rating = document.querySelector('input[name="rating"]:checked').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const params = new URLSearchParams({
            product_id: product_id,
            rating: rating,
            page: page
        });

        fetch(`{{ route('seller.reviews.index') }}?${params}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('reviews-container').innerHTML = data.html;
                document.getElementById('pagination-container').innerHTML = data.pagination;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi Kesalahan Saat Memfilter Ulasan');
        });
    }
</script>
@endsection
