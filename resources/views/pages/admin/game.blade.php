@extends('layouts.template')

@section('content')
<div class="px-5 my-3">
    <h5 class="fw-semibold text-dark">Daftar Game</h5>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <a href="{{ route('admin.games.create') }}" class="text-decoration-none link-footer">Tambah Game Baru</a> |
    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Kembali ke Dashboard</a>

    <br><br>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th style="border: 1px solid gray; padding: 5px;">No</th>
                <th style="border: 1px solid gray; padding: 5px;">Gambar</th>
                <th style="border: 1px solid gray; padding: 5px;">Nama Game</th>
                <th style="border: 1px solid gray; padding: 5px;">Kategori Tersedia</th>
                <th style="border: 1px solid gray; padding: 5px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($games as $game)
            <tr>
                <td style="border: 1px solid gray; padding: 5px;">{{ ($games->currentPage() - 1) * $games->perPage() + $loop->iteration }}</td>
                <td style="border: 1px solid gray; padding: 5px;">
                    @if($game->game_img)
                        <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="100">
                    @else
                        <span>No Image</span>
                    @endif
                </td>
                <td style="border: 1px solid gray; padding: 5px;">{{ $game->game_name }}</td>
                <td style="border: 1px solid gray; padding: 5px;">
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
                <td style="border: 1px solid gray; padding: 5px;">
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
                <td colspan="5" style="border: 1px solid gray; padding: 5px;">Belum ada game</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <br>
    {{ $games->links() }}
</div>
@endsection
