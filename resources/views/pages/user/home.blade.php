@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        Home
    </div>

    <div class="container-fluid d-flex gap-5 p-4">
        @foreach ($products as $product)
            <div>
                <h3>{{ $product->product_name }}</h3>
                @if ($product->product_img)
                    <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}"
                        width="200" height="200">
                @else
                    <div>No Image</div>
                @endif
                <p>Game: {{ $product->game->game_name }}</p>
                <p>Category: {{ $product->category->category_name }}</p>
                <p>Price: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>
@endsection
