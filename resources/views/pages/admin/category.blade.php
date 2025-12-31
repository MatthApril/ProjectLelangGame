@extends('layouts.templateadmin')

@section('content')
<div class="my-3">
    <h5 class="fw-semibold text-dark">Manajemen Kategori</h5>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <hr>

    <h6 class="fw-bold">{{ $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h6>

    <form action="{{ $editCategory ? route('admin.categories.update', $editCategory->category_id) : route('admin.categories.store') }}" method="POST">
        @csrf
        @if($editCategory)
            @method('PUT')
        @endif

        <div>
            <label for="category_name">Nama Kategori *</label><br>
            <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $editCategory->category_name ?? '') }}" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" required>
            @error('category_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <button type="submit" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;">
            {{ $editCategory ? 'Update Kategori' : 'Simpan Kategori' }}
        </button>

        @if($editCategory)
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Batal</a>
        @endif
    </form>

    <hr>

    <h6 class="fw-bold">Daftar Kategori (Total: {{ $categories->count() }})</h6>

    <table border="1" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $category->category_name }}
                    @if($category->deleted_at)
                        <span style="color: red; font-weight: bold;">(Dihapus)</span>
                    @endif
                </td>
                <td>
                    @if($category->deleted_at)
                        <span class="badge bg-danger">Nonaktif</span>
                    @else
                        <span class="badge bg-success">Aktif</span>
                    @endif
                </td>
                <td>
                    @if($category->deleted_at)
                        <form action="{{ route('admin.categories.restore') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $category->category_id }}">
                            <button type="submit" onclick="return confirm('Aktifkan kembali kategori ini?')" 
                                    style="padding: 5px 10px; border: 1px solid green; background: green; color: white; border-radius: 5px; cursor: pointer;">
                                Aktifkan Kembali
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.categories.edit', $category->category_id) }}" 
                           class="text-decoration-none link-footer">
                            Edit
                        </a>
                        
                        <form action="{{ route('admin.categories.destroy', $category->category_id) }}" 
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menonaktifkan kategori ini? Produk terkait akan ikut dinonaktifkan.')" 
                                    style="padding: 5px 10px; border: 1px solid red; background: red; color: white; border-radius: 5px; cursor: pointer;">
                                Nonaktifkan
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="border: 1px solid gray; padding: 5px; text-align: center;">
                    Belum ada kategori
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection