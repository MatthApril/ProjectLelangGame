@extends('layouts.template')

@section('title', 'Ulasan Toko | LelangGame')

@section('content')
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

    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Statistik Ulasan</h4>
            <p><strong>Total Ulasan:</strong> {{ $totalReviews }}</p>
            <p><strong>Rating Toko:</strong> {{ number_format(Auth::user()->shop->shop_rating, 1) }}/5 ⭐</p>
        </div>
    </div>

    <div class="row mb-4">
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
    </div>

    <hr>

    <form id="filter-form" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label><strong>Filter Produk:</strong></label>
                <select name="product_id" id="product_id" class="form-control">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_id }}" {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label><strong>Filter Rating:</strong></label>
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="rating" id="rating-all" value="" {{ !request('rating') ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-all">Semua</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-5" value="5" {{ request('rating') == '5' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-5">⭐⭐⭐⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-4" value="4" {{ request('rating') == '4' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-4">⭐⭐⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-3" value="3" {{ request('rating') == '3' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-3">⭐⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-2" value="2" {{ request('rating') == '2' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-2">⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-1" value="1" {{ request('rating') == '1' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-1">⭐</label>
                </div>
            </div>
        </div>
    </form>

    <div id="reviews-container">
        @include('partials.reviews_table', ['comments' => $comments])
    </div>

    <div class="mt-3">
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
        alert('Terjadi kesalahan saat memfilter ulasan');
    });
}
</script>
@endsection
