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

    <hr>

    <table border="1" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Game</th>
                <th>Kategori Tersedia</th>
                <th>Status</th>
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
                <td>
                    {{ $game->game_name }}
                    @if($game->deleted_at)
                        <span style="color: red; font-weight: bold;">(Dihapus)</span>
                    @endif
                </td>
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
                    @if($game->deleted_at)
                        <span class="badge bg-danger">Nonaktif</span>
                    @else
                        <span class="badge bg-success">Aktif</span>
                    @endif
                </td>
                <td>
                    @if($game->deleted_at)
                        <form action="{{ route('admin.games.restore') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $game->game_id }}">
                            <button type="submit" 
                                    onclick="return confirm('Aktifkan kembali game ini? Produk terkait akan ikut diaktifkan.')" 
                                    style="padding: 5px 10px; border: 1px solid green; background: green; color: white; border-radius: 5px; cursor: pointer;">
                                Aktifkan Kembali
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.games.edit', $game->game_id) }}" 
                           class="text-decoration-none link-footer">
                            Edit
                        </a>

                        <form action="{{ route('admin.games.destroy', $game->game_id) }}" 
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menonaktifkan game ini? Produk terkait akan ikut dinonaktifkan.')" 
                                    style="padding: 5px 10px; border: 1px solid red; background: red; color: white; border-radius: 5px; cursor: pointer;">
                                Nonaktifkan
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Belum ada game</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $games->links() }}
</div>
@endsection