@extends('layouts.templateadmin')

@section('content')
<div class="container my-3 text-dark">
    <h5 class="fw-semibold text-dark">Game</h5>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahGame">
        Tambah Game Baru
    </button><br>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <hr>

    @if($editGame)
        <h6 class="fw-bold">Edit Game</h6>

        <form action="{{ $editGame ? route('admin.games.update', $editGame->game_id) : route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($editGame)
                @method('PUT')
            @endif

            <div>
                <label for="game_name">Nama Game *</label><br>
                <input type="text" id="game_name" name="game_name" value="{{ old('game_name', $editGame->game_name ?? '') }}" class="form-control" required>
                @if($game && $game->game_img)
                    <div>
                        <p>Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="200">
                    </div>
                @endif
                @error('game_name')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            <br>

            <button type="submit" class="btn btn-primary">{{ $editGame ? 'Update Game' : 'Simpan Game' }}</button>

            @if($editGame)
                <a href="{{ route('admin.games.index') }}" class="text-decoration-none link-footer">Batal</a>
            @endif
        </form>
    @else
        <h6 class="fw-bold">Daftar Game (Total: {{ $games->count() }})</h6>
        
        <div class="table-responsive">
            <table border="1" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Game</th>
                        <th>Kategori Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                    <tr>
                        <td>{{ ($games->currentPage() - 1) * $games->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($game->game_img)
                                <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="100">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $game->game_name }}</td>
                        <td>
                        @forelse($game->gamesCategories as $gc)
                                @if($gc->category)
                                    <span style="{{ $gc->category->deleted_at ? 'color: red; text-decoration: line-through;' : '' }}">
                                        {{ $gc->category->category_name }}
                                        @if($gc->category->deleted_at)
                                            <small>(Dihapus)</small>
                                        @endif
                                    </span>
                                    @if(!$loop->last), @endif
                                @endif
                            @empty
                                <span style="color: orange;">Tidak ada kategori</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('admin.games.edit', $game->game_id) }}" class="text-decoration-none link-footer">Edit</a>
        
                            <form action="{{ route('admin.games.destroy', $game->game_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus game ini?')" style="padding: 5px; border: 1px solid gray; border-radius: 5px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">Belum ada game</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $games->links() }}
    @endif

</div>
<div class="modal" tabindex="-1" id="modalTambahGame">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Game Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div>
                        <label for="game_name">Nama Game *</label><br>
                        <input type="text" id="game_name" name="game_name" value="{{ old('game_name', $editGame->game_name ?? '') }}" class="form-control" required><br>
                        @error('game_name')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                        <label for="game_img">Gambar Game *</label><br>
                        <input type="file" id="game_img" name="game_img" accept="image/*" required>
                        <p>Format: JPG, PNG, JPEG. Max: 2MB</p>
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
                                <p>Belum ada kategori.<br> 
                                    {{-- <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Tambah kategori terlebih dahulu</a> --}}
                                </p>
                            @endforelse

                            @error('categories')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        @error('game_img')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Game</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
