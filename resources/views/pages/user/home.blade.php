@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <h3 class="fw-semibold">Home</h3>
        <hr>
        <h3 class="fw-semibold">FAQ</h3>
        <div class="row d-flex justify-content-center">
            <div class="col-md-12">

            </div>
        </div>
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

    <div class="container-fluid">
        <h3>Owners</h3>
        <table border="1" class="table table-striped">
            <tr>
                <th>No</th>
                <th>Owner</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            @foreach ($owners as $index => $owner)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $owner->username }}</td>
                    <td>{{ $owner->email }}</td>
                    <td>
                        <a href="{{ route('user.chat.show', ['userId' => $owner->user_id]) }}">
                            <button class="btn btn-primary">
                                Chat
                            </button>
                        </a>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
