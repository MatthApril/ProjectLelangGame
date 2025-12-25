{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\admin\category.blade.php --}}
@extends('layouts.template')

@section('content')
<div>
    <h1>Manajemen Kategori</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>

    <hr>

    <h2>{{ $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h2>

    <form action="{{ $editCategory ? route('admin.categories.update', $editCategory->category_id) : route('admin.categories.store') }}" method="POST">
        @csrf
        @if($editCategory)
            @method('PUT')
        @endif

        <div>
            <label for="category_name">Nama Kategori *</label>
            <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $editCategory->category_name ?? '') }}" required>
            @error('category_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <button type="submit">{{ $editCategory ? 'Update Kategori' : 'Simpan Kategori' }}</button>

        @if($editCategory)
            <a href="{{ route('admin.categories.index') }}">Batal</a>
        @endif
    </form>

    <hr>

    <h2>Daftar Kategori (Total: {{ $categories->count() }})</h2>

    <table border="1" cellpadding="10">
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
                    <a href="{{ route('admin.categories.edit', $category->category_id) }}">Edit</a>

                    <form action="{{ route('admin.categories.destroy', $category->category_id) }}"method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">Belum ada kategori</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
