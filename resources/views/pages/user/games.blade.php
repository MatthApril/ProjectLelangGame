@extends('layouts.template')

@section('title', 'Games | LelangGame')

@section('content')
<div class="container-fluid my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Games</li>
    </ol>
    </nav>
    <h3 class="fw-semibold">Eksplor Game Di LelangGame</h3>

    <form method="GET" action="{{ route('games.index') }}">
        <div class="d-flex align-items-center justify-content-center gap-2">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="search" name="search" placeholder="Cari Nama Game" value="{{ request('search') }}" aria-label="Search" class="form-control" autocomplete="off" autofocus>
            </div>
            <button type="submit" class="btn btn-success">Cari</button>
            {{-- @if(request('search'))
                <a href="{{ route('games.index') }}" class="btn btn-secondary">Reset</a>
            @endif --}}
        </div>
    </form>

    <br><br>

    <div>
        @forelse($games as $game)
        <div>
            @if($game->game_img)
                <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="200">
            @else
                <p>[ No Image ]</p>
            @endif
            <h3>{{ $game->game_name }}</h3>
            <p>{{ $game->products_count }} Produk Tersedia</p>
            <a href="{{ route('games.detail', $game->game_id) }}">Lihat Produk</a>
        </div>
        <br>
        @empty
        <h5 class="text-center fw-semibold">Tidak Ada Game Ditemukan</h5>
        @endforelse
    </div>

    <br>
    {{ $games->links() }}
</div>
@endsection
