{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\admin\category.blade.php --}}
@extends('layouts.templateadmin')

@section('content')
<div class="container my-3">
    <h5 class="fw-semibold text-dark">Manajemen Kategori</h5>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    {{-- <a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Kembali ke Dashboard</a> --}}

    <hr>

    <h6 class="fw-bold">{{ $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h6>

    <form action="{{ $editCategory ? route('admin.categories.update', $editCategory->category_id) : route('admin.categories.store') }}" method="POST">
        @csrf
        @if($editCategory)
            @method('PUT')
        @endif

        <div>
            <label for="category_name">Nama Kategori *</label><br>
            <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $editCategory->category_name ?? '') }}" class="form-control" required>
            @error('category_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <button type="submit" class="btn btn-primary">{{ $editCategory ? 'Update Kategori' : 'Simpan Kategori' }}</button>

        @if($editCategory)
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Batal</a>
        @endif
    </form>

    <hr>

    <h6 class="fw-bold">Daftar Kategori (Total: {{ $categories->count() }})</h6>

    <div class="table-responsive">
        <table border="1" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->category_id) }}" class="text-decoration-none link-footer">Edit</a>
    
                        <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus kategori ini?')" style="padding: 5px; border: 1px solid gray; border-radius: 5px;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 5px;">Belum ada kategori</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
