@extends('layouts.templateadmin')

@section('content')
<div class="my-3">
    <h5 class="fw-semibold text-dark">Daftar Game</h5>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <a href="{{ route('admin.games.create') }}" class="text-decoration-none link-footer">Tambah Game Baru</a>
    {{-- <a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Kembali ke Dashboard</a> --}}

    <hr>

    <table border="1" class="table table-striped">
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

    {{ $games->links() }}
</div>
@endsection
