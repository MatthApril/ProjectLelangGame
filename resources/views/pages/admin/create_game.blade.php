@extends('layouts.templateadmin')

@section('content')
<div class="my-3">
    <h5 class="fw-semibold text-dark">{{ $game ? 'Edit Game' : 'Tambah Game Baru' }}</h5>

    <hr>

    <form action="{{ $game ? route('admin.games.update', $game->game_id) : route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($game)
            @method('PUT')
        @endif

        <div>
            <label for="game_name">Nama Game *</label><br>
            <input type="text" id="game_name" name="game_name" value="{{ old('game_name', $game->game_name ?? '') }}" style="padding: 5px; width: 331px; border: 1px solid gray; border-radius: 5px;" required>
            @error('game_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div>
            <label for="game_img">Gambar Game {{ $game ? '' : '*' }}</label><br>
            <input type="file" id="game_img" name="game_img" accept="image/*" {{ $game ? '' : 'required' }}>
            <p>Format: JPG, PNG, JPEG. Max: 2MB</p>
            @error('game_img')
                <span style="color: red;">{{ $message }}</span>
            @enderror

            @if($game && $game->game_img)
                <div>
                    <p>Gambar saat ini:</p>
                    <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="200">
                </div>
            @endif
        </div>

        <br>

        <div>
            <label>Kategori Game *<br> 
                {{-- <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">(Kelola Kategori)</a> --}}
            </label>
            <br>
            @php
                $selectedCategories = $game ? $game->gamesCategories->pluck('category_id')->toArray() : old('categories', []);
            @endphp

            @forelse($categories as $category)
                <label>
                    <input type="checkbox"name="categories[]"value="{{ $category->category_id }}"{{ in_array($category->category_id, $selectedCategories) ? 'checked' : '' }}> {{ $category->category_name }}
                </label>
                <br>
            @empty
                <p>Belum ada kategori.<br> <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Tambah kategori terlebih dahulu</a></p>
            @endforelse

            @error('categories')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <button type="submit" style="padding: 5px; border: 1px solid gray; border-radius: 5px;">{{ $game ? 'Update Game' : 'Simpan Game' }}</button>
        <a href="{{ route('admin.games.index') }}" class="text-decoration-none link-footer">Batal</a>
    </form>
</div>
@endsection
