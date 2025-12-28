@extends('layouts.template')

@section('content')
<div>
    <h1>Daftar Game</h1>

    <form method="GET" action="{{ route('games.index') }}">
        <input type="text" name="search" placeholder="Cari game..." value="{{ request('search') }}">
        <button type="submit">Cari</button>
        @if(request('search'))
            <a href="{{ route('games.index') }}">Reset</a>
        @endif
    </form>

    <br><br>

    <div>
        @forelse($games as $game)
        <div>
            @if($game->game_img)
                <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="200">
            @else
                <p>[No Image]</p>
            @endif
            <h3>{{ $game->game_name }}</h3>
            <p>{{ $game->products_count }} Produk Tersedia</p>
            <a href="{{ route('games.detail', $game->game_id) }}">Lihat Produk</a>
        </div>
        <br>
        @empty
        <p>Tidak ada game ditemukan</p>
        @endforelse
    </div>

    <br>
    {{ $games->links() }}
</div>
@endsection
