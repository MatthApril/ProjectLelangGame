@extends('layouts.template')

@section('title', 'Semua Game | LelangGame')

@section('content')
<div class="container my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Semua Game</li>
    </ol>
    </nav>
    <h3 class="fw-semibold">Eksplor Game Di LelangGame</h3>

    <form method="GET" action="{{ route('games.index') }}">
        <div class="d-flex align-items-center justify-content-center gap-2">
            <div class="input-group">
                <input type="search" name="search" placeholder="Cari Nama Game" value="{{ request('search') }}" aria-label="Search" class="form-control" autocomplete="off" autofocus>
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
            </div>
            {{-- @if(request('search'))
                <a href="{{ route('games.index') }}" class="btn btn-secondary">Reset</a>
            @endif --}}
        </div>
    </form>

    <div class="row">
        @forelse($games as $game)
        <div class="col-md-2 mt-4">
            <div class="card">
                @if($game->game_img)
                    <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" class="card-img-top">
                @endif
                <div class="card-body">
                    <h5 class="card-title fw-semibold">{{ $game->game_name }}</h5>
                    <span class="text-secondary">{{ $game->products_count }} Produk Tersedia</span>
                    <a href="{{ route('games.detail', $game->game_id) }}" class="btn btn-primary btn-sm mt-3 float-end">Lihat Produk <i class="bi bi-caret-right-fill"></i></a>
                </div>
            </div>
        </div>
        @empty
        <h5 class="text-center fw-semibold">Tidak Ada Game Ditemukan</h5>
        @endforelse
    </div>

    <br>
    {{ $games->links() }}
</div>
@endsection
